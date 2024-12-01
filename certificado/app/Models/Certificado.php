<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    protected $table = "certificados";

    protected $fillable = [
        'codigo_autenticador'
    ];

    public $timestamps = false;
}
