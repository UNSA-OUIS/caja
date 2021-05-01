<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResumenDiarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resumen_diario', function (Blueprint $table) {
            $table->id();
            $table->string('fecha_envio');
            $table->string('fecha_emision');
            $table->string('serie', 4);
            $table->string('correlativo', 8);
            $table->string('ticket');
            $table->enum('estado', ['noEnviado', 'observado', 'rechazado', 'anulado', 'aceptado']);
            $table->text('observaciones');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resumen_diario');
    }
}
