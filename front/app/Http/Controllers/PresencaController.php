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
            foreach($presencas as $ref_inscricao => $ref_pessoa)
            {
                Log::info($ref_pessoa ." pessoa ". $ref_inscricao ." inscricao");
                $response = $this->apiGatewayService->storePresencas($ref_inscricao, $ref_pessoa);
                Log::info($response);

            }
            
            return response()->json(['success' => true, 'message' => "Inscrição realizada com sucesso"]);
        }
        catch(\Exception $e)
        {
            return response()->json(['sucess', $e->getMessage()],500);
        }
    }
}
