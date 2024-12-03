<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmailInscricao(Request $request)
    {
        $user = $request->input('user');
        $inscricao = $request->input('inscricao');

        Log::info('Enviando e-mail de confirmação de inscrição para ' . $user['email']);
        Log::info('Dados do usuário: ' . json_encode($user));
        Log::info('Dados da inscrição: ' . json_encode($inscricao));
        
        Mail::to('gustavo.rahmeier@universo.univates.br')->send(new SendEmail('emails.inscricao', $user, $inscricao));
    }

    public function sendEmailPresenca(Request $request)
    {
        $user = $request->input('user');
        $inscricao = $request->input('inscricao');

        Mail::to('gustavo.rahmeier@universo.univates.br')->send(new SendEmail('emails.presenca', $user, $inscricao));
    }

    public function sendEmailCancelamento(Request $request)
    {
        $user = $request->input('user');
        $inscricao = $request->input('inscricao');

        Mail::to('gustavo.rahmeier@universo.univates.br')->send(new SendEmail('emails.cancelamento', $user, $inscricao));
    }
}
