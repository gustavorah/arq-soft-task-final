<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificado;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CertificadoController extends Controller
{
    public function index()
    {
        return response()->json(Certificado::all(), 200);
    }

    public function show($id)
    {
        $certificado = Certificado::find($id);
        if (!$certificado) {
            return response()->json(['error' => 'Certificado não encontrado'], 404);
        }
        return response()->json($certificado, 200);
    }

    public function store(Request $request)
    {
        $codigo_autenticador = $request->input('codigo_autenticador');
        $evento = $request->input('evento');

        Log::info('Codigo '. $codigo_autenticador);
        
        $caminhoRelativo = 'certificados/' . $codigo_autenticador . '.pdf';
        $caminhoCompleto = storage_path('app/public/' . $caminhoRelativo);
        Log::info($caminhoCompleto);
        Log::info($caminhoRelativo);

        if (Storage::exists($caminhoCompleto) || Storage::exists($caminhoRelativo)) {
            // Codificar em Base64 o conteúdo do arquivo existente

            $conteudoBase64 = base64_encode(Storage::get($caminhoRelativo));
            return response()->json([
                'caminho' => $caminhoRelativo,
                'base64' => $conteudoBase64
            ]);
        }

        // Gerar PDF e salvar
        $this->gerarPDF($codigo_autenticador, $evento);

        if (Storage::exists($caminhoCompleto) || Storage::exists($caminhoRelativo)) {
            $conteudoBase64 = base64_encode(Storage::get($caminhoRelativo));
            return response()->json([
                'caminho' => $caminhoRelativo,
                'base64' => $conteudoBase64
            ]);
        }

        return response()->json(['error' => 'Erro ao gerar o certificado'], 500);
    }

    public function update(Request $request, $id)
    {
        $certificado = Certificado::find($id);
        if (!$certificado) {
            return response()->json(['error' => 'Certificado não encontrado'], 404);
        }
        $certificado->update($request->all());
        return response()->json($certificado, 200);
    }

    public function destroy($id)
    {
        $certificado = Certificado::find($id);
        if (!$certificado) {
            return response()->json(['error' => 'Certificado não encontrado'], 404);
        }
        $certificado->delete();
        return response()->json(['message' => 'Certificado excluído'], 200);
    }

    public function getCertificado($codigo_autenticador)
    {
        $certificado = Certificado::where('codigo_autenticador', $codigo_autenticador)->first();

        return $certificado;
    }

    public function gerarPDF($codigo_autenticador, $evento)
    {
        $caminhoCompleto = 'app/public/certificados/' . $codigo_autenticador . '.pdf';

        if (Storage::exists($caminhoCompleto)) {
            Log::info("aslkdjas");
            return true;
        }

        $dados = [
            'titulo' => $evento['descricao'],
            'conteudo' => "Certificado em " . $evento['descricao'],
            'codigo_autenticador' => 'Código autenticador: ' . $codigo_autenticador
        ];

        $layout = $evento['layout_certificado'] ?? 'primeiroLayout';

        $pdf = Pdf::loadView($layout, $dados);
        Log::info("aslkdjas");

        // Salvar o arquivo no caminho correto
        $pdf->save(storage_path($caminhoCompleto));

        return true;
    }
}
