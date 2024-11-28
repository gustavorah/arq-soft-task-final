<?php

namespace App\Http\Controllers;

use App\Services\ApiGatewayService;
use Exception;
use Illuminate\Http\Request;

class EventosController extends Controller
{
    private $apiGatewayService;

    public function __construct(ApiGatewayService $apiGatewayService)
    {
        $this->apiGatewayService = $apiGatewayService;
    }
    public function index()
    {
        try
        {
            $eventos = $this->apiGatewayService->getEventos();
            $user = request()->user();

            return view('eventos', compact('eventos', 'user'));
        }
        catch (Exception $e)
        {
            throw new Exception("Sem eventos" . $e->getMessage());
        }
    }

    public function getEvento($id)
    {
        try
        {
            return $this->apiGatewayService->getEvento($id);
        }
        catch (Exception $e)
        {
            throw new Exception("Evento $id não encontrado, ". $e->getMessage());
        }
    }

    public function show($id)
    {
        try
        {
            $evento = $this->getEvento($id);
            $evento = array_shift($evento);
            return view("eventos.editar", compact("evento"));
        }
        catch (Exception $e)
        {
            throw new Exception("Evento não disponível". $e->getMessage());
        }
    }

    public function atualizar(Request $request, $id)
    {
        // Valida os dados do formulário
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'dt_inicio' => 'nullable|date',
            'dt_fim' => 'nullable|date',
        ]);

        // Recupera o evento pelo ID
        $evento = $this->getEvento($id);

        // Verifica se o evento existe
        if (!$evento) {
            return redirect()->route('eventos.index')->with('error', 'Evento não encontrado');
        }

        // Atualiza os dados do evento
        $evento->descricao = $validated['descricao'];
        $evento->dt_inicio = $validated['dt_inicio'] ?? $evento->dt_inicio;
        $evento->dt_fim = $validated['dt_fim'] ?? $evento->dt_fim;
        $evento->save();

        // Redireciona para a lista de eventos com sucesso
        return redirect()->route('eventos.index')->with('success', 'Evento atualizado com sucesso!');
    }
}
