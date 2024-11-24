<?php

use App\Services\ApiGatewayService;
use Illuminate\Foundation\Http\FormRequest;

class EventosRequest extends FormRequest
{
    private $apiGatewayService;

    public function __construct(ApiGatewayService $apiGatewayService)
    {
        parent::__construct();
        $this->apiGatewayService = $apiGatewayService;
    }
    public function getEventos()
    {
        try
        {
            $response = $this->apiGatewayService->getEventos();
        }
        catch (Exception $e)
        {
            throw new Exception("Sem eventos");
        }
    }
}