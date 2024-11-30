<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("certificados", function (Blueprint $table) {
            $table->id();
            $table->string("codigo_autenticador");
            $table->dateTime("dt_criacao")->default(DB::raw("now()"));
    });

    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("certificados");
    }
};
