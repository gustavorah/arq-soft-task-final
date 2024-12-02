<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteLogsTable extends Migration
{
    public function up()
    {
        Schema::create('route_logs', function (Blueprint $table) {
            $table->id();
            $table->string('route');
            $table->string('method');
            $table->string('ip_address');
            $table->text('user_agent');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('route_logs');
    }
}
