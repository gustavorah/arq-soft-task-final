<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'descricao',
        'dt_criacao',
        'dt_inicio',
        'dt_fim'
    ];

    protected $table = 'evento';
}
