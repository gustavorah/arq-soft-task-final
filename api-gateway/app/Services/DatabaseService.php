<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class DatabaseService
{
    protected $dbHost;
    protected $dbName;
    protected $dbUser;
    protected $dbPassword;
    protected $backupPath;

    public function __construct()
    {
        $this->dbHost = env('DB_HOST', 'localhost');
        $this->dbName = env('DB_DATABASE');
        $this->dbUser = env('DB_USERNAME');
        $this->dbPassword = env('DB_PASSWORD');
        $this->backupPath = storage_path('backups');
    }

    /**
     * Realiza o backup do banco de dados usando pg_dump.
     */
    public function backupDatabase(): string
    {
        $backupFile = $this->backupPath . '/' . $this->dbName . '_' . date('Y_m_d_His') . '.sql';

        // Cria o diretório se não existir
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }

        $command = sprintf(
            'PGPASSWORD=%s pg_dump -h %s -U %s %s > %s',
            escapeshellarg($this->dbPassword),
            escapeshellarg($this->dbHost),
            escapeshellarg($this->dbUser),
            escapeshellarg($this->dbName),
            escapeshellarg($backupFile)
        );

        $output = null;
        $resultCode = null;
        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            Log::error('Erro ao realizar o backup do banco de dados.', ['output' => $output]);
            throw new \Exception('Erro ao realizar o backup do banco de dados.');
        }

        return $backupFile;
    }

    /**
     * Restaura o banco de dados a partir de um arquivo de backup.
     */
    public function restoreDatabase(string $backupFile): void
    {
        if (!file_exists($backupFile)) {
            throw new \Exception('Arquivo de backup não encontrado: ' . $backupFile);
        }

        $command = sprintf(
            'PGPASSWORD=%s psql -h %s -U %s -d %s -f %s',
            escapeshellarg($this->dbPassword),
            escapeshellarg($this->dbHost),
            escapeshellarg($this->dbUser),
            escapeshellarg($this->dbName),
            escapeshellarg($backupFile)
        );

        $output = null;
        $resultCode = null;
        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            Log::error('Erro ao restaurar o banco de dados.', ['output' => $output]);
            throw new \Exception('Erro ao restaurar o banco de dados.');
        }
    }
}
