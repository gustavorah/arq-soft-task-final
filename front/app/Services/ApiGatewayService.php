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
        $this->baseUrl = env('URL_API_GATEWAY', 'http://api-gateway:8000/api');

        $this->http = Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->timeout(60);
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

    public function getUserByEmail($email)
    {
        return $this->http->post("/users/email", ['email' => $email])->json();
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
        return $this->http->get("/eventos/{$ref_evento}", ['id'=> $ref_evento])->json();
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

    public function storePresencas($ref_pessoas, $ref_inscricoes)
    {
        return $this->http->post('/presencas', ['ref_pessoa' => $ref_pessoas, 'ref_inscricao_evento' => $ref_inscricoes])->json();
    }

    public function hasInscricaoByUserAndEvento($request)
    {
        return $this->http->post('/inscricao-evento/verificar-inscricao', $request)->json();
    }

    public function hasPresencaByUserAndInscricao($ref_pessoa, $ref_inscricao_evento)
    {
        return $this->http->post('/presencas/verificar-presenca', ['ref_pessoa' => $ref_pessoa, 'ref_inscricao_evento' => $ref_inscricao_evento])->json();
    }

    public function cancelarInscricao($ref_inscricao)
    {
        $dt_cancelamento = date('Y-m-d H:i:s');
        return $this->http->put("/inscricao-evento/{$ref_inscricao}", ['dt_cancelamento' => $dt_cancelamento])->json();
    }

    public function getInscricaoById($ref_inscricao)
    {
        return $this->http->get("/inscricao-evento/{$ref_inscricao}")->json();
    }

    public function gerarCertificado($codigo_autenticador, $evento)
    {
        return $this->http->post("/certificado", ["codigo_autenticador"=> $codigo_autenticador, 'evento' => $evento])->json();
    }

    public function autenticarCertificado($codigo_autenticador)
    {
        return $this->http->post('/certificado/auth', ['codigo_autenticador' => $codigo_autenticador])->json();
    }

    public function sendEmailConfirmacaoInscricao($user, $inscricao)
    {
        return $this->http->post('/mail/inscricao', ['user' => $user, 'inscricao' => $inscricao])->json();
    }

    public function sendEmailConfirmacaoPresenca($user, $inscricao)
    {
        return $this->http->post('/mail/presenca', ['user' => $user, 'inscricao' => $inscricao])->json();
    }

    public function sendEmailCancelamentoInscricao($user, $inscricao)
    {
        return $this->http->post('/mail/cancelamento', ['user' => $user, 'inscricao' => $inscricao])->json();
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
