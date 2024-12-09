<?php

namespace App\Http\Requests\Auth;

use App\Services\ApiGatewayService;
use Exception;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    protected $apiGatewayService;

    public function __construct(ApiGatewayService $apiGatewayService)
    {
        parent::__construct();
        $this->apiGatewayService = $apiGatewayService;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        try 
        {
            $response = $this->apiGatewayService->authenticateUser(
                $this->input('email'),
                $this->input('password')
            );
            Log::info('', ['response' => $response]);
            // Verificar o retorno do response
            if ($response['access_token']) 
            {
                // Log para depuração
                $user = $this->apiGatewayService->getUserByEmail($this->input('email'));
                Auth::loginUsingId($user['id']);
                Log::info('Usuário autenticado com sucesso', ['email' => $this->input('email')]);
            } 
            else 
            {
                throw new Exception('Credenciais inválidas');
            }
        } 
        catch (Exception $e) 
        {
            Log::error($response, ['email'=> $this->input('email')]);
            Log::error('Erro de autenticação', ['email' => $this->input('email'), 'error' => $e->getMessage()]);
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
