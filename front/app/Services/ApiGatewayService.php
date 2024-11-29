<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Log;

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

    public function authenticateUser($email, $password)
    {
        return $this->http->post('/users/auth', ['email' => $email, 'password' => md5($password)])->json();
    }

    public function getEventos()
    {
        return $this->http->get("/eventos/")->json();
    }

    public function getInscricoesByUser($user)
    {
        return $this->http->post("/inscricao-evento/inscricoes-user", ["ref_pessoa"=> $user['id']])->json();
    }

    public function getEvento($ref_evento)
    {
        return $this->http->get('/eventos/{eventos}', ['id'=> $ref_evento])->json();
    }

    public function storeInscricaoEvento($params)
    {
        return $this->http->post('/inscricao-evento', ["ref_pessoa" => $params['ref_pessoa'], 'ref_evento'=> $params['ref_evento']])->json();
    }

    public function atualizarEvento($evento, $id)
    {
        return $this->http->put("/eventos/{$id}", $evento)->json();
    }

    public function getAllInscricoesByRefEvento($ref_evento)
    {
        return $this->http->post("/inscricao-evento/inscricoes", ['ref_evento' => $ref_evento])->json();
    }

    public function storePresencas($ref_inscricao_evento, $ref_pessoa)
    {
        return $this->http->post('/presencas', ['ref_inscricao_evento' => $ref_inscricao_evento, 'ref_pessoa' => $ref_pessoa])->json();
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
