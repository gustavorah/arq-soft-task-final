<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create("evento", function(Blueprint $table) {
            $table->id();
            $table->string("descricao");
            $table->string("layout_certificado")->nullable();
            $table->timestamp("dt_cricao")->default(now());
            $table->timestamp("dt_inicio")->nullable();
            $table->timestamp("dt_fim")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("evento");
    }
};
