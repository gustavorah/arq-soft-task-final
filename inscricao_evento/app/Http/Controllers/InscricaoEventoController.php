<?php

namespace App\Http\Controllers;

use App\Models\InscricaoEvento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;

class InscricaoEventoController extends Controller
{
    public function index()
    {
        $inscricoes_eventos = InscricaoEvento::all();

        return response()->json($inscricoes_eventos);
    }

    public function show($id)
    {
        $inscrico_evento = InscricaoEvento::find($id);

        return response()->json($inscrico_evento);
    }
    
    public function store(Request $request)
    {
        $inscricao_evento = new InscricaoEvento($request->all());

        $inscricao_evento->save();

        return response()->json($inscricao_evento);
    }


    public function update(Request $request, $id)
    {
        $inscricao_evento = InscricaoEvento::find($id);

        $inscricao_evento->update($request->all());

        return response()->json($inscricao_evento);
    }

    public function destroy(Request $request, $id)
    {
        $inscricao_evento = InscricaoEvento::find($id);

        $inscricao_evento->delete();

        return response()->json($inscricao_evento);
    }
}
