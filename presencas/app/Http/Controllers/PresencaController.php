<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PresencaModel;
use Illuminate\Support\Facades\Log;

class PresencaController extends Controller
{
    // Lista todas as presenças
    public function index()
    {
        return response()->json(PresencaModel::all());
    }

    // Exibe uma presença específica
    public function show($id)
    {
        $presenca = PresencaModel::find($id);
        if (!$presenca) {
            return response()->json(['error' => 'Presença não encontrada'], 404);
        }

        return response()->json($presenca);
    }

    // Cria uma nova presença
    public function store(Request $request)
    {
        $presenca = PresencaModel::create($request->all());

        return response()->json($presenca, 200);
    }

    // Atualiza uma presença existente
    public function update(Request $request, $id)
    {
        $presenca = PresencaModel::find($id);

        if (!$presenca) {
            return response()->json(['error' => 'Presença não encontrada'], 404);
        }

        $data = $request->validate([
            'ref_pessoa' => 'sometimes|integer',
            'ref_inscricao_evento' => 'sometimes|integer',
        ]);

        $presenca->update($data);

        return response()->json($presenca);
    }

    // Remove uma presença
    public function destroy($id)
    {
        $presenca = PresencaModel::find($id);

        if (!$presenca) {
            return response()->json(['error' => 'Presença não encontrada'], 404);
        }

        $presenca->delete();

        return response()->json(['message' => 'Presença deletada com sucesso']);
    }

    public function hasPresenca(Request $request)
    {
        $presenca = PresencaModel::where('ref_pessoa', $request['ref_pessoa'])
        ->where('ref_inscricao_evento', $request['ref_inscricao_evento'])
        ->first();

        return $presenca ? true : false;
    }
}
