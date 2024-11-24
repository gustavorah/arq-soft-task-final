<?php

namespace App\Http\Controllers;

use App\Services\ApiGatewayService;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
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

            return view('dashboard', compact('eventos'));
        }
        catch (Exception $e)
        {
            throw new Exception("Sem eventos" . $e->getMessage());
        }
    }
}
