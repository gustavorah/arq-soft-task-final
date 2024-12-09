<?php

namespace App\Http\Controllers;

use App\Services\ApiGatewayService;
use App\Services\ApiGatewayServiceOuter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CertificadoController extends Controller
{
    private $apiGatewayService;
    private $apiGatewayServiceOuter;

    public function __construct(ApiGatewayService $apiGatewayService, ApiGatewayServiceOuter $apiGatewayServiceOuter)
    {
        $this->apiGatewayService = $apiGatewayService;
        $this->apiGatewayServiceOuter = $apiGatewayServiceOuter;
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

    public function autenticar(Request $request)
    {
        try
        {
            $codigo_autenticador = $request->input('codigo_autenticador');
            $autenticado = $this->apiGatewayServiceOuter->autenticarCertificado($codigo_autenticador);
            Log::info($autenticado);
            if ($autenticado)
            {
                Log::info('Autenticado');
                $msg = 'Certificado autenticado com sucesso!';
            }
            else
            {
                $msg = 'Não foi possível autenticar o certificado!';
            }

            return view('certificado.autenticar', compact('msg'));
        }
        catch(\Exception $e)
        {
            $msg = 'Não foi possível autenticar o certificado, erro: ' . $e->getMessage();
            return view('certificado.autenticar', compact('msg'));
        }
    }
}
