<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InscricaoEvento extends Model
{
    protected $table = "inscricao_evento";

    protected $fillable = [
        "ref_pessoa",
        "ref_evento",
        "dt_inscricao",
        "dt_cancelamento"
    ];

    public $timestamps = false;
}
