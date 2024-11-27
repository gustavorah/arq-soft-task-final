<?php

namespace App\Http\Controllers;

use App\Services\ApiGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InscricaoEventoController extends Controller
{
    private $apiGatewayService;

    public function __construct(ApiGatewayService $apiGatewayService)
    {
        $this->apiGatewayService = $apiGatewayService;
    }

    public function getAllInscricoes()
    {
        $user = request()->user();

        $inscricoes = $this->apiGatewayService->getInscricoesByUser($user);
        $eventos = [];
        foreach ($inscricoes as $key => $inscricao) 
        {
            try
            {
                $eventos = $this->apiGatewayService->getEvento($inscricao['ref_evento']);
            }
            catch (\Exception $e)
            {
                throw new \Exception("Evento" . $inscricao['ref_evento'] . " não encontrado, ". $e->getMessage());
            }
        }
        
        return view("dashboard", compact('eventos'));
    }

    public function store(Request $request)
    {
        try
        {
            $inscricao_evento = $this->apiGatewayService->storeInscricaoEvento($request->all());

            return response()->json(['success' => true, 'message' => "Inscrição realizada com sucesso"]);
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno no servidor'], 500);
        }
    }
}
