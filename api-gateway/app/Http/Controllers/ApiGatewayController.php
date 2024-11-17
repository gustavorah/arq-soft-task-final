<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiGatewayController extends Controller
{
    protected $serviceUrls = [
        'users' => 'http://127.0.0.1:8000/api',
        // Add more services as needed
    ];

    /**
     * Forward request to appropriate microservice
     */
    public function forwardRequest(Request $request, $service, $path = '')
    {
        if (!array_key_exists($service, $this->serviceUrls)) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        $serviceUrl = $this->serviceUrls[$service];
        $fullUrl = rtrim($serviceUrl, '/') . '/' . ltrim($path, '/');

        try {
            // Get the request method
            $method = $request->method();

            // Forward the request with its original method
            $response = Http::withHeaders($this->getForwardableHeaders($request));

            // Handle different HTTP methods
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
                    return response()->json(['error' => 'Method not supported'], 405);
            }

            // Return the response from the service
            return response()
                ->json($response->json())
                ->setStatusCode($response->status());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Service unavailable',
                'message' => $e->getMessage(),
                'service' => $service,
                'url' => $fullUrl
            ], 503);
        }
    }

    /**
     * Get headers that should be forwarded
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

        // Ensure JSON content type
        $headers['Accept'] = 'application/json';
        $headers['Content-Type'] = 'application/json';

        return $headers;
    }
}
