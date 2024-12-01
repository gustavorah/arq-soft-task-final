<?php

namespace App\Http\Controllers;

use App\Services\ApiGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

            $evento = $this->apiGatewayService->getEvento($inscricao['ref_evento']);
            $resposta = $this->apiGatewayService->gerarCertificado($codigo_autenticador, $evento);
            
            $pdf = base64_decode($resposta['base64']);
            $caminho = $resposta['caminho'];
            if (! file_exists(storage_path("tmp/$caminho")))
            {
                file_put_contents(storage_path("tmp/$caminho"), $pdf);
            }
            
            if (file_exists(storage_path("tmp/$caminho")))
            {
                return response()->download(storage_path("tmp/$caminho"));
            }
        }
        catch(\Exception $e)
        {
            return response()->json(['Não foi possível gerar o certificado, erro: ' . $e->getMessage()],404);
        }
    }
}
