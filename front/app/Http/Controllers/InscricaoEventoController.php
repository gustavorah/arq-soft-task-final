<?php

namespace App\Http\Controllers;

use App\Services\ApiGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use function Laravel\Prompts\alert;

class InscricaoEventoController extends Controller
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

    public function getAllInscricoes()
    {
        $user = request()->user();
        
        if ($this->isOffline())
        {
            $inscricoes = $this->getInscricoesOffline();
        }
        else
        {
            $inscricoes = $this->apiGatewayService->getInscricoesByUser($user);
        }

        $arrEventos = [];
        $eventoController = new EventosController($this->apiGatewayService);
        foreach ($inscricoes as $key => $inscricao) 
        {
            try
            {
                if ($this->isOffline())
                {
                    $inscricoes[$key]['evento'] = $eventoController->getEventoOffline($inscricao['ref_evento']);
                }
                else
                {
                    $inscricoes[$key]['evento'] = $this->apiGatewayService->getEvento($inscricao['ref_evento']);
                }
                if ($inscricoes[$key]['evento']['dt_fim'] < date('Y-m-d H:i:s'))
                {
                    $inscricoes[$key]['evento']['pode_gerar'] = true;
                }
                else
                {
                    $inscricoes[$key]['evento']['pode_gerar'] = false;
                }
            }
            catch (\Exception $e)
            {
                throw new \Exception("Evento" . $inscricao['ref_evento'] . " não encontrado, ". $e->getMessage());
            }
        }
        return view("dashboard", compact('inscricoes'));
    }

    public function getInscricoesOffline()
    {
        $localStorage = Session::get('dados_offline');
        return $localStorage['inscricao_evento'];
    }

    public function store(Request $request)
    {
        try
        {
            if (true)//$this->isOffline())
            {
                $inscricao_evento = $this->storeInscricaoEventoOffline($request->all());
            }
            else
            {
                $inscricao_evento = $this->apiGatewayService->storeInscricaoEvento($request->all());
            }

            $this->apiGatewayService->sendEmail();
            
            return response()->json(['success' => true, 'message' => "Inscrição realizada com sucesso"]);
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno no servidor'], 500);
        }
    }

    public function storeInscricaoEventoOffline(array $data)
    {
        $sincronizarController = new SincronizacaoController();
        $localStorage = $sincronizarController->sincronizar();
        Log::info($localStorage);
        // $inscricoes = $localStorage['inscricao_evento'];
        // $inscricoes[] = $data;
        // Session::put('dados_offline', ['inscricao_evento' => $inscricoes]);
        // Log::info($inscricoes);
        // return $data;
    }

    public function storeRapido(Request $request)
    {
        try
        {
            $email = $request->input('email');
            $ref_evento = $request->input('evento_id');
            
            if ($this->isOffline())
            {
                $user = $this->getUserByEmailOffline($email);

                if (empty($user))
                {
                    $user = $this->createUserOffline($email);
                }
                $eventoController = new EventosController($this->apiGatewayService);
                $evento = $eventoController->getEventoOffline($ref_evento);
                $this->storeInscricaoEventoOffline(['ref_pessoa' => $user['id'], 'ref_evento' => $ref_evento]);
            }
            else
            {
                $user = $this->apiGatewayService->getUserByEmail($email);
            
                if ($user && $this->apiGatewayService->hasInscricaoByUserAndEvento(['ref_pessoa' => $user['id'], 'ref_evento' => $ref_evento]))
                {
                    $evento = $this->apiGatewayService->getEvento($ref_evento);
                    $error = true;
                    return view('eventos.inscrever', compact('evento', 'error'));
                }
    
                if (empty($user))
                {
                    $user = $this->apiGatewayService->createUser(['email' => $email, 'password' => md5('teste')]);
                }
    
                $evento = $this->apiGatewayService->getEvento($ref_evento);
                $data = ['ref_pessoa' =>$user['id'], 'ref_evento' => $ref_evento];
                $inscricao_evento = $this->apiGatewayService->storeInscricaoEvento($data);
    
                $this->apiGatewayService->sendEmail();
            }

            $success = true;
            return view('eventos.inscrever', compact('evento', 'success'));
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            $error = true;
            return view('eventos.inscrever', compact('evento', 'error'));
        }
    }

    public function getUserByEmailOffline($email)
    {
        $localStorage = Session::get('dados_offline');
        $users = $localStorage['users'];
        foreach ($users as $user)
        {
            if ($user['email'] == $email)
            {
                return $user;
            }
        }
        return null;
    }

    public function createUserOffline($email)
    {
        $localStorage = Session::get('dados_offline');
        $users = $localStorage['users'];
        $users[] = ['email' => $email, 'password' => md5('teste')];
        Session::put('dados_offline', ['users' => $users]);
        return $users[count($users) - 1];
    }

    public function getAllInscricoesByRefEvento($ref_evento)
    {
        try
        {
            if ($this->isOffline())
            {
                $inscricao_evento = $this->getAllInscricoesByRefEventoOffline($ref_evento);
            }
            else
            {
                $inscricao_evento = $this->apiGatewayService->getAllInscricoesByRefEvento($ref_evento);
            }
            return $inscricao_evento;
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno no servidor'], 500);
        }
    }

    public function getAllInscricoesByRefEventoOffline($ref_evento)
    {
        $localStorage = Session::get('dados_offline');
        $inscricoes = $localStorage['inscricao_evento'];
        $inscricoes_evento = [];
        foreach ($inscricoes as $inscricao)
        {
            if ($inscricao['ref_evento'] == $ref_evento)
            {
                $inscricoes_evento[] = $inscricao;
            }
        }
        return $inscricoes_evento;
    }

    public function cancelar($id)
    {
        try
        {
            if ($this->isOffline())
            {
                $this->cancelarInscricaoOffline($id);
            }
            else
            {
                $this->apiGatewayService->cancelarInscricao($id);
            }

            return $this->getAllInscricoes();
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return alert('Não foi possível cancelar sua inscrição');
            // return response()->json(['success'=> false, 'message'=> 'Não foi possível cancelar sua inscrição' . $e->getMessage()],500);
        }
    }

    public function cancelarInscricaoOffline($id)
    {
        $localStorage = Session::get('dados_offline');
        $inscricoes = $localStorage['inscricao_evento'];
        foreach ($inscricoes as $key => $inscricao)
        {
            if ($inscricao['id'] == $id)
            {
                unset($inscricoes[$key]);
            }
        }
        Session::put('dados_offline', ['inscricao_evento' => $inscricoes]);
        return true;
    }
}
