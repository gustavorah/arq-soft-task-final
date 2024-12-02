<?php

namespace App\Http\Controllers;

use App\Services\ApiGatewayService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class EventosController extends Controller
{
    private $apiGatewayService;
    private $offline = false;

    public function __construct(ApiGatewayService $apiGatewayService)
    {
        $this->apiGatewayService = $apiGatewayService;
        $this->offline = Session::get('offline', false);
    }

    public function isOffline()
    {
        return $this->offline;
    }

    public function index()
    {
        try
        {
            if ($this->offline)
            {
                $eventos = $this->getEventosOffline();
            }
            else
            {
                $eventos = $this->apiGatewayService->getEventos();
            }
            $user = request()->user();

            return view('eventos', compact('eventos', 'user'));
        }
        catch (Exception $e)
        {
            throw new Exception("Sem eventos" . $e->getMessage());
        }
    }

    public function getEventosOffline()
    {
        $localStorage = Session::get('dados_offline');
        return $localStorage['evento'];
    }

    public function getEvento($id)
    {
        try
        {
            if ($this->offline)
            {
                $evento = $this->getEventoOffline($id);
            }
            else
            {
                return $this->apiGatewayService->getEvento($id);
            }
        }
        catch (Exception $e)
        {
            throw new Exception("Evento $id não encontrado, ". $e->getMessage());
        }
    }

    public function getEventoOffline($id)
    {
        $localStorage = Session::get('dados_offline');
        return $localStorage['evento'][$id];
    }

    public function show($id)
    {
        try
        {
            if ($this->offline)
            {
                $evento = $this->getEventoOffline($id);
            }
            else
            {
                $evento = $this->getEvento($id);
            }
            
            $inscricaoEvento = new InscricaoEventoController($this->apiGatewayService);
            $inscricoes_evento = $inscricaoEvento->getAllInscricoesByRefEvento($id);

            foreach ($inscricoes_evento as $index => $inscricao)
            {
                $user = $this->apiGatewayService->getUserById($inscricao['ref_pessoa']);
                $inscricoes_evento[$index]['nome'] = $user['name'];
                $inscricoes_evento[$index]['email'] = $user['email'];

                if ($this->apiGatewayService->hasPresencaByUserAndInscricao($inscricao['ref_pessoa'], $inscricao['id']))
                {
                    unset($inscricoes_evento[$index]);
                }
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
        // $evento = $evento; // Extrai o primeiro elemento do array

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

    public function inscreverPessoa($ref_evento)
    {
        $evento = $this->getEvento($ref_evento);

        return view('eventos.inscrever', compact( 'evento'));
    }
}
