<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail()
    {
        $details = [
            'title' => 'Título do E-mail',
            'body' => 'Este é o corpo do e-mail para teste.'
        ];

        Mail::to('netav48229@bflcafe.com')->send(new SendEmail($details));

        return response()->json(['message' => 'E-mail enviado com sucesso!']);
    }
}
