<?php

namespace App\Http\Controllers;

use App\Services\ApiGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            $arrInscricao = [];
            $arrPessoas   = [];
            
            foreach($presencas as $ref_inscricao => $ref_pessoa)
            {
                $arrInscricao = array_merge($arrInscricao, [$ref_inscricao]);
                $arrPessoas = array_merge($arrPessoas, [$ref_pessoa]);
            }
            
            // Agora passamos um único objeto com as duas arrays
            $response = $this->apiGatewayService->storePresencas(
                $arrPessoas,
                $arrInscricao
            );
            
            return response()->json(['success' => true, 'message' => "Inscrição realizada com sucesso"]);
        }
        catch(\Exception $e)
        {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
