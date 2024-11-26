<?php

namespace App\Http\Controllers;

use App\Services\ApiGatewayService;
use Exception;
use Illuminate\Http\Request;

class EventosController extends Controller
{
    private $apiGatewayService;

    public function __construct(ApiGatewayService $apiGatewayService)
    {
        $this->apiGatewayService = $apiGatewayService;
    }
    public function index()
    {
        try
        {
            $eventos = $this->apiGatewayService->getEventos();

            return view('eventos', compact('eventos'));
        }
        catch (Exception $e)
        {
            throw new Exception("Sem eventos" . $e->getMessage());
        }
    }

    public function getEvento($id)
    {
        try
        {
            return $this->apiGatewayService->getEvento($id);
        }
        catch (Exception $e)
        {
            throw new Exception("Evento $id não encontrado, ". $e->getMessage());
        }
    }
}
