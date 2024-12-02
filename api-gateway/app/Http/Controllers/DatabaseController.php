<?php

namespace App\Http\Controllers;

use App\Services\DatabaseService;
use Illuminate\Http\Request;

class DatabaseController extends Controller
{
    protected $databaseService;

    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    public function backup()
    {
        try {
            $backupFile = $this->databaseService->backupDatabase();
            return response()->json(['message' => 'Backup realizado com sucesso.', 'file' => $backupFile]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function restore(Request $request)
    {
        $backupFile = $request->input('backup_file');

        try {
            $this->databaseService->restoreDatabase($backupFile);
            return response()->json(['message' => 'Banco de dados restaurado com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
