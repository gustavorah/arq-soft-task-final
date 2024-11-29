<?php

namespace App\Http\Controllers;

use App\Services\ApiGatewayService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

            $inscricaoEvento = new InscricaoEventoController($this->apiGatewayService);
            $inscricoes_evento = $inscricaoEvento->getAllInscricoesByRefEvento($id);
            
            foreach ($inscricoes_evento as $index => $inscricao)
            {
                $user = $this->apiGatewayService->getUserById($inscricao['ref_pessoa']);
                $inscricoes_evento[$index]['nome'] = $user['name'];
            }
            
            return view("eventos.editar", compact("evento", "inscricoes_evento"));
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
        if (!$evento) 
        {
            return redirect()->route('eventos')->with('error', 'Evento não encontrado');
        }

        // Atualiza os dados do evento
        $evento = array_shift($evento); // Extrai o primeiro elemento do array
        $evento['descricao'] = $validated['descricao'];
        $evento['dt_inicio'] = isset($validated['dt_inicio']) ? Carbon::parse($validated['dt_inicio'])->format('Y-m-d H:i:s') : $evento['dt_inicio'];
        $evento['dt_fim'] = isset($validated['dt_fim']) ? Carbon::parse($validated['dt_fim'])->format('Y-m-d H:i:s') : $evento['dt_fim'];
        
        try
        {
            $return = $this->apiGatewayService->atualizarEvento($evento, $id);

            return redirect()->route('eventos')->with('success', 'Evento atualizado com sucesso!');
        }
        catch (Exception $e)
        {
            return redirect()->route('eventos.editar')->with('error', 'Não foi possível atualizar o evento');
        }
    }
}
