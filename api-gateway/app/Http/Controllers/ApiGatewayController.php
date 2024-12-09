<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiGatewayController extends Controller
{
    protected $serviceUrls = [
        // 'users' => 'http://127.0.0.1:8000/api',
        // 'eventos' => 'http://127.0.0.1:8002/api',
        // 'inscricao-evento' => 'http://127.0.0.1:8004/api',
        // 'presencas' => 'http://127.0.0.1:8005/api',
        // 'certificado' => 'http://127.0.0.1:8006/api',
        // 'mail' => 'http://127.0.0.1:8007/api'
        'users' => 'http://users:8000/api',
        'eventos' => 'http://eventos:8000/api',
        'inscricao-evento' => 'http://inscricao_evento:8000/api',
        'presencas' => 'http://presencas:8000/api',
        'certificado' => 'http://certificado:8000/api',
        'mail' => 'http://mail-service:8000/api'
    ];

    /**
     * Encaminha a requisição para o microserviço apropriado
     */
    public function forwardRequest(Request $request, $service, $path = '')
    {
        if (!array_key_exists($service, $this->serviceUrls)) {
            return response()->json(['error' => 'Serviço não encontrado'], 404);
        }

        $serviceUrl = $this->serviceUrls[$service];

        $fullUrl = rtrim($serviceUrl, '/') . '/' . ltrim($path, '/');

        try {
            // Obter o método da requisição
            $method = $request->method();

            // Encaminhar a requisição com o método original
            $response = Http::withHeaders($this->getForwardableHeaders($request));
            // Manipular diferentes métodos HTTP
            switch ($method) {
                case 'GET':
                    $response = $response->get($fullUrl);
                    break;
                case 'POST':
                    $response = $response->post($fullUrl, $request->all());
                    break;
                case 'PUT':
                    $response = $response->put($fullUrl, $request->all());
                    break;
                case 'DELETE':
                    $response = $response->delete($fullUrl);
                    break;
                default:
                    return response()->json(['error' => 'Método não suportado'], 405);
            }

            // Retornar a resposta do serviço
            return response()
                ->json($response->json())
                ->setStatusCode($response->status());
        } catch (\Exception $e) {
            Log::error('Erro ao encaminhar requisição: ' . $e->getMessage());
            return response()->json([
                'error' => 'Serviço indisponível',
                'message' => $e->getMessage(),
                'service' => $service,
                'url' => $fullUrl
            ], 503);
        }
    }

    /**
     * Obter os cabeçalhos que devem ser encaminhados
     */
    private function getForwardableHeaders(Request $request)
    {
        $headers = [];
        $forwardableHeaders = [
            'accept',
            'content-type',
            'user-agent',
            'x-requested-with'
        ];

        foreach ($forwardableHeaders as $header) {
            if ($request->header($header)) {
                $headers[$header] = $request->header($header);
            }
        }

        // Garantir que o tipo de conteúdo seja JSON
        $headers['Accept'] = 'application/json';
        $headers['Content-Type'] = 'application/json';

        return $headers;
    }
}
