<?php

namespace App\Http\Controllers;

use App\Jobs\SendConfirmacaoPresencaEmail;
use App\Services\ApiGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PresencaController extends Controller
{
    private $apiGatewayService;
    public function __construct(ApiGatewayService $apiGatewayService)
    {
        $this->apiGatewayService = $apiGatewayService;
    }

    public function store(Request $request)
    {
        try
        {
            $presencas = $request->input("presencas");
            $user = request()->user();
            $ref_evento = $request->input("evento_id");

            foreach($presencas as $ref_inscricao => $ref_pessoa)
            {
                $response = $this->apiGatewayService->storePresencas(
                    $ref_pessoa,
                    $ref_inscricao
                );

                $inscricao_evento = $this->apiGatewayService->getInscricaoById($ref_inscricao);

                SendConfirmacaoPresencaEmail::dispatch($user, $inscricao_evento);
            }
            
            // Agora passamos um único objeto com as duas arrays
            
            $evento_controller = new EventosController($this->apiGatewayService);
            
            return $evento_controller->show($ref_evento);
        }
        catch(\Exception $e)
        {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
