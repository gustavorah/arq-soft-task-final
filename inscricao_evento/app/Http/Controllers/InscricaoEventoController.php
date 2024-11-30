<?php

namespace App\Http\Controllers;

use App\Models\InscricaoEvento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\In;

use function Pest\Laravel\json;

class InscricaoEventoController extends Controller
{
    // public function index()
    // {
    //     $inscricoes_eventos = InscricaoEvento::all();

    //     return response()->json($inscricoes_eventos);
    // }

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

    public function getInscricoesByUser(Request $request)
    {
        $inscricoes_eventos = InscricaoEvento::where("ref_pessoa", $request['ref_pessoa'])
                                             ->whereNull('dt_cancelamento')
                                              ->get();
        
        return response()->json($inscricoes_eventos);
    }

    public function getAllInscricoesByRefEvento(Request $request)
    {
        Log::info($request->all());
        $inscricoes_eventos = InscricaoEvento::where("ref_evento", "=", $request['ref_evento'])
                                                ->whereNull('dt_cancelamento')     
                                                ->get();
        
        return response()->json($inscricoes_eventos);
    }

    public function hasInscricaoByUserAndEvento(Request $request)
    {
        $inscricao_evento = InscricaoEvento::where("ref_evento", "=", $request['ref_evento'])
                                           ->where("ref_pessoa", '=', $request['ref_pessoa'])
                                           ->whereNull('dt_cancelamento')
                                           ->first();

        return $inscricao_evento ? true : false;
    }
}
