<?php

namespace App\Http\Controllers;

use App\Jobs\SendCancelamentoInscricaoEmail;
use App\Jobs\SendConfirmacaoInscricaoEmail;
use App\Services\ApiGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use function Laravel\Prompts\alert;

class InscricaoEventoController extends Controller
{
    private $apiGatewayService;
    public function __construct(ApiGatewayService $apiGatewayService)
    {
        $this->apiGatewayService = $apiGatewayService;
    }

    public function getAllInscricoes()
    {
        $user = request()->user();
        
        $inscricoes = $this->apiGatewayService->getInscricoesByUser($user);

        $arrEventos = [];
        $eventoController = new EventosController($this->apiGatewayService);
        foreach ($inscricoes as $key => $inscricao) 
        {
            try
            {
                $inscricoes[$key]['evento'] = $this->apiGatewayService->getEvento($inscricao['ref_evento']);
             
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

    public function store(Request $request)
    {
        try
        {
            $user = request()->user();
            $inscricao_evento = $this->apiGatewayService->storeInscricaoEvento($request->all());

            $inscricao_evento = $this->apiGatewayService->getInscricaoById($inscricao_evento['id']);

            SendConfirmacaoInscricaoEmail::dispatch($user, $inscricao_evento);
            
            return response()->json(['success' => true, 'message' => "Inscrição realizada com sucesso"]);
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno no servidor'], 500);
        }
    }


    public function storeRapido(Request $request)
    {
        try
        {
            $email = $request->input('email');
            $ref_evento = $request->input('evento_id');
            
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
            
            $inscricao_evento = $this->apiGatewayService->getInscricaoById($inscricao_evento['id']);
            
            SendConfirmacaoInscricaoEmail::dispatch($user, $inscricao_evento);

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

    public function getAllInscricoesByRefEvento($ref_evento)
    {
        try
        {
            $inscricao_evento = $this->apiGatewayService->getAllInscricoesByRefEvento($ref_evento);
         
            return $inscricao_evento;
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno no servidor'], 500);
        }
    }

    public function cancelar($id)
    {
        try
        {
            $this->apiGatewayService->cancelarInscricao($id);

            $user = request()->user();
            $inscricao_evento = $this->apiGatewayService->getInscricaoById($id);
            SendCancelamentoInscricaoEmail::dispatch($user, $inscricao_evento);

            return $this->getAllInscricoes();
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return alert('Não foi possível cancelar sua inscrição');
            // return response()->json(['success'=> false, 'message'=> 'Não foi possível cancelar sua inscrição' . $e->getMessage()],500);
        }
    }
}
