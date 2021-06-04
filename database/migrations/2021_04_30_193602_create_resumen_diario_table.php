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
            //$table->string('serie', 4);
            $table->string('correlativo', 8);
            $table->string('ticket')->nullable();
            $table->enum('estado', ['observado', 'rechazado', 'anulado', 'aceptado'])->nullable();
            $table->boolean('enviado')->nullable()->default(false);
            $table->text('observaciones')->nullable();
            $table->string('url_xml')->nullable();
            $table->string('url_cdr')->nullable();
            $table->string('url_pdf')->nullable();
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
