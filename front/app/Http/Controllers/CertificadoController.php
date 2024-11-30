<?php

namespace App\Http\Controllers;

use App\Services\ApiGatewayService;
use Illuminate\Http\Request;

class CertificadoController extends Controller
{
    private $apiGatewayService;

    public function __construct(ApiGatewayService $apiGatewayService)
    {
        $this->apiGatewayService = $apiGatewayService;
    }

    public function gerar($ref_inscricao)
    {
        try
        {
            $inscricao = $this->apiGatewayService->getInscricaoById($ref_inscricao);
            $user = request()->user();

            if (! $inscricao)
            {
                return response()->json(['Inscrição não disponível'], 404);
            }

            $codigo_autenticador = md5($inscricao['id'] . "" . $user['email']);

            $resposta = $this->apiGatewayService->gerarCertificado($codigo_autenticador);
        }
        catch(\Exception $e)
        {
            return response()->json(['Não foi possível gerar o certificado, erro: ' . $e->getMessage()],404);
        }
    }
}
