<?php

namespace App\Http\Controllers;

use App\Services\ApiGatewayService;
use Illuminate\Http\Request;

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
                $response = $this->apiGatewayService->storePresencas($ref_inscricao, $ref_pessoa);
            }

            return response()->json($response);
        }
        catch(\Exception $e)
        {
            return response()->json(['sucess', $e->getMessage()],500);
        }
    }
}
