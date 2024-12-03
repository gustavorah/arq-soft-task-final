<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCancelamentoInscricaoEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $inscricao;

    public function __construct($user, $inscricao)
    {
        $this->user = $user;
        $this->inscricao = $inscricao;
    }

    public function handle($apiGatewayService)
    {
        $apiGatewayService->sendEmailCancelamentoInscricao($this->user, $this->inscricao);
    }
}