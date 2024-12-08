<?php

namespace App\Http\Requests;

use App\Services\ApiGatewayService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterRequest extends FormRequest
{
    private $apiGatewayService;

    public function __construct(ApiGatewayService $apiGatewayService)
    {
        $this->apiGatewayService = $apiGatewayService;
    }

    public function store(Request $request)
    {
        try
        {
            $user = $this->apiGatewayService->createUser($request->all());

            Auth::loginUsingId($user['id']);

            return view('dashboard', compact('inscricoes'));
        }
        catch (\Exception $e)
        {
            return view('register');
        }
    }
}
