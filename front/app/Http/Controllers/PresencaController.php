<?php

namespace App\Http\Controllers;

use App\Services\ApiGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PresencaController extends Controller
{
    private $apiGatewayService;
    private $offline = false;
    public function __construct(ApiGatewayService $apiGatewayService)
    {
        $this->apiGatewayService = $apiGatewayService;
        $this->offline = Session::get('offline', false);
    }

    public function isOffline()
    {
        return $this->offline;
    }

    public function store(Request $request)
    {
        try
        {
            $presencas = $request->input("presencas");

            $ref_evento = $request->input("evento_id");

            foreach($presencas as $ref_inscricao => $ref_pessoa)
            {
                if ($this->isOffline())
                {
                    $this->storePresencasOffline($ref_pessoa, $ref_inscricao);
                }
                else
                {
                    $response = $this->apiGatewayService->storePresencas(
                    $ref_pessoa,
                    $ref_inscricao
                );

                    $this->apiGatewayService->sendEmail();
                }
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

    public function storePresencasOffline($ref_pessoa, $ref_inscricao)
    {
        Log::info("Presença salva offline para a inscrição $ref_inscricao e pessoa $ref_pessoa");
        $localStorage = Session::get('dados_offline');
        $presencas = $localStorage['presencas'];
        $presencas[] = ['ref_pessoa' => $ref_pessoa, 'ref_inscricao_evento' => $ref_inscricao];
        Session::put('dados_offline', ['presencas' => $presencas]);
        return true;
    }
}
