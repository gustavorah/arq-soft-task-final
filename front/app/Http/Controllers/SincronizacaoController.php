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

    public function sincronizarOficiais(Request $request)
    {
        try {
            Log::info('Iniciando sincronização de dados oficiais');
            $dadosOficiais = $request->input('dadosOficiais', []);

            DB::beginTransaction();

            // Sincroniza usuários
            if (isset($dadosOficiais['users']) && is_array($dadosOficiais['users'])) {
                foreach ($dadosOficiais['users'] as $usuario) {
                    if (!isset($usuario['email'])) {
                        Log::warning('Usuário sem email encontrado, pulando...');
                        continue;
                    }
                    
                    Log::info('Sincronizando usuário: ' . $usuario['email']);

                    $usuarioExistente = DB::table('users')->where('email', $usuario['email'])->first();

                    if (! $usuarioExistente) {
                        DB::table('users')->insert([
                            'email' => $usuario['email'],
                            'password' => md5($usuario['password'])
                        ]);
                    }
                }
            }

            // Sincroniza inscrições
            if (isset($dadosOficiais['inscricao_evento']) && is_array($dadosOficiais['inscricao_evento'])) {
                foreach ($dadosOficiais['inscricao_evento'] as $inscricao) {
                    if (!isset($inscricao['email'])) {
                        Log::warning('Inscrição sem email encontrada, pulando...');
                        continue;
                    }

                    $user = DB::table('users')
                        ->where('email', $inscricao['email'])
                        ->first();

                    if (!$user) {
                        Log::warning('Usuário não encontrado para o email: ' . $inscricao['email']);
                        continue;
                    }

                    $inscricaoData = [
                        'ref_evento' => $inscricao['ref_evento'],
                        'ref_pessoa' => $user->id,
                        'dt_inscricao' => $inscricao['dt_inscricao'],
                        'dt_cancelamento' => $inscricao['dt_cancelamento'] ?? null
                    ];

                    DB::table('inscricao_evento')->updateOrInsert(
                        [
                            'ref_evento' => $inscricao['ref_evento'],
                            'ref_pessoa' => $user->id
                        ],
                        $inscricaoData
                    );
                }
            }

            // Sincroniza presenças
            if (isset($dadosOficiais['presencas']) && is_array($dadosOficiais['presencas'])) {
                foreach ($dadosOficiais['presencas'] as $presenca) {
                    
                    if (!isset($presenca['email']) || !isset($presenca['ref_evento'])) {
                        Log::warning('Presença com dados incompletos encontrada, pulando...');
                        continue;
                    }

                    $user = DB::table('users')
                        ->where('email', $presenca['email'])
                        ->first();
                    if (!$user) {
                        Log::warning('Usuário não encontrado para o email: ' . $presenca['email']);
                        continue;
                    }

                    $inscricao = DB::table('inscricao_evento')
                        ->where('ref_evento', $presenca['ref_evento'])
                        ->where('ref_pessoa', $user->id)
                        ->first();

                    if (!$inscricao) {
                        Log::warning('Inscrição não encontrada para o evento e usuário');
                        continue;
                    }

                    $presencaExistente = DB::table('presencas')
                        ->where('ref_inscricao_evento', $inscricao->id)
                        ->where('ref_pessoa', $user->id)
                        ->first();

                    if (!$presencaExistente) {
                        DB::table('presencas')->insert([
                            'ref_inscricao_evento' => $inscricao->id,
                            'ref_pessoa' => $user->id,
                        ]);
                    }
                }
            }

            DB::commit();
            Log::info('Sincronização de dados oficiais concluída com sucesso');
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao sincronizar dados oficiais: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao sincronizar dados oficiais',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
