<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->char('cui', 8);
            $table->char('nues', 3);
            $table->string('serie', 4);
            $table->string('correlativo', 8);
            $table->decimal('total');
            $table->enum('estado', ['noEnviado', 'observado', 'rechazado', 'anulado', 'aceptado']);
            $table->text('observaciones');
            $table->string('url_xml');
            $table->string('url_cdr');
            $table->string('url_pdf');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comprobantes');
    }
}
