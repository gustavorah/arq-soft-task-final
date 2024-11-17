<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class ApiGatewayService
{
    protected string $baseUrl;
    protected PendingRequest $http;

    public function __construct()
    {
        $this->baseUrl = env('URL_API_GATEWAY', 'http://127.0.0.1:8001/api');
        
        $this->http = Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])
            ->timeout(30);
    }

    // Users endpoints
    public function getAllUsers()
    {
        return $this->http->get('/users')->json();
    }

    public function getUserById($id)
    {
        return $this->http->get("/users/{$id}")->json();
    }

    public function createUser(array $userData)
    {
        return $this->http->post('/users', $userData)->json();
    }

    public function updateUser($id, array $userData)
    {
        return $this->http->put("/users/{$id}", $userData)->json();
    }

    public function deleteUser($id)
    {
        return $this->http->delete("/users/{$id}")->json();
    }

    // Helper method to handle errors
    protected function handleResponse($response)
    {
        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception($response->body(), $response->status());
    }
}