<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\ApiGatewayController;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Contracts\Providers\JWT;

class AuthController extends Controller
{
    private $apiGatewayController;

    public function __construct(ApiGatewayController $apiGatewayController)
    {
        $this->apiGatewayController = $apiGatewayController;
    }
    public function login(Request $request)
    {
        // $credentials = $request->only('email', 'password');
        
        $response = $this->apiGatewayController->forwardRequest($request, 'users', "auth");

        $userData = json_decode($response->getContent(), true);

        $user = new User($userData);

        $token = JWTAuth::fromUser($user);

        if (! $user['id'] || ! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => null
        ]);
    }
}