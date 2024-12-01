<?php

namespace App\Http\Controllers;

use App\Services\ApiGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\alert;

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

        $arrEventos = [];
        foreach ($inscricoes as $key => $inscricao) 
        {
            try
            {
                $inscricoes[$key]['evento'] = $this->apiGatewayService->getEvento($inscricao['ref_evento']);
            }
            catch (\Exception $e)
            {
                throw new \Exception("Evento" . $inscricao['ref_evento'] . " não encontrado, ". $e->getMessage());
            }
        }
        return view("dashboard", compact('inscricoes'));
    }

    public function store(Request $request)
    {
        try
        {
            if ($this->apiGatewayService->hasInscricaoByUserAndEvento($request))
            {
                return response()->json(['sucess' => false, 'message' => 'Usuário já possui inscrição neste evento']);
            }

            $inscricao_evento = $this->apiGatewayService->storeInscricaoEvento($request->all());

            return response()->json(['success' => true, 'message' => "Inscrição realizada com sucesso"]);
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno no servidor'], 500);
        }
    }

    public function getAllInscricoesByRefEvento($ref_evento)
    {
        try
        {
            $inscricao_evento = $this->apiGatewayService->getAllInscricoesByRefEvento($ref_evento);
            
            return $inscricao_evento;
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno no servidor'], 500);
        }
    }

    public function cancelar($id)
    {
        try
        {
            $this->apiGatewayService->cancelarInscricao($id);

            return $this->getAllInscricoes();
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return alert('Não foi possível cancelar sua inscrição');
            // return response()->json(['success'=> false, 'message'=> 'Não foi possível cancelar sua inscrição' . $e->getMessage()],500);
        }
    }
}
