<?php

namespace App\Http\Controllers;

use App\Models\Eventos;
use Illuminate\Http\Request;

class EventosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eventos = Eventos::all();

        return response()->json($eventos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $evento = new Eventos($request->all());

        $evento->save();

        return response()->json($evento);
    }

    /**
     * Display the specified resource.
     */
    public function show(Eventos $eventos)
    {
        $eventos = Eventos::findOrFail($eventos);

        return response()->json($eventos);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Eventos $eventos)
    {
        $eventos = Eventos::findOrFail($eventos);

        $eventos->update($request->all());

        return response()->json($eventos);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Eventos $eventos)
    {
        // $event
    }
}
