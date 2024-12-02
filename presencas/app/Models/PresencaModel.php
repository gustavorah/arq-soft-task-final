<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresencaModel extends Model
{
    protected $table = "presencas";

    protected $fillable = [
        "ref_pessoa",
        "ref_inscricao_evento"
    ];

    public $timestamps = false;
}
