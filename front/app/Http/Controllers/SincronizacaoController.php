<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SincronizacaoController extends Controller
{

    
    public function sincronizar(): JsonResponse
    {
        try {
            // Adiciona log para verificar a conexão
            Log::info('Iniciando sincronização de dados.');

            // Lista todas as tabelas do schema public
            $tabelas = DB::connection('pgsql')
                ->select("
                    SELECT table_name 
                    FROM information_schema.tables 
                    WHERE table_schema = 'public' 
                    AND table_type = 'BASE TABLE'
                ");

            // Verifica se as tabelas foram encontradas
            if (empty($tabelas)) {
                Log::warning('Nenhuma tabela encontrada no schema public.');
                return response()->json(['error' => 'Nenhuma tabela encontrada'], 404);
            }

            $dadosTodasTabelas = [];
            $tabelas_para_sincronizar = ['users', 'inscricao_evento', 'evento', 'presencas'];
            // Busca dados de cada tabela
            foreach ($tabelas as $tabela) {
                if (in_array($tabela->table_name, $tabelas_para_sincronizar)) {
                    $nomeTabela = $tabela->table_name;
                    Log::info("Sincronizando dados da tabela: $nomeTabela");

                $dadosTodasTabelas[$nomeTabela] = DB::connection('pgsql')
                    ->table($nomeTabela)
                    ->get();
                }
            }
            Log::info('Sincronização concluída com sucesso.');
            Session::put('dados_offline', $dadosTodasTabelas);
            Session::put('offline', ! Session::get('offline'));
            return response()->json($dadosTodasTabelas);
        } catch (\Exception $e) {
            Log::error('Erro ao sincronizar dados: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao sincronizar dados',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function setOfflineMode(Request $request)
    {
        Log::info('Setando modo offline');
        $offline = $request->input('offline', false);
        session(['offline' => $offline]);
        return response()->json(['success' => true]);
    }
}
