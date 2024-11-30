<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificado;

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
        $certificado = Certificado::create($request->all());
        return response()->json($certificado, 200);
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
}
