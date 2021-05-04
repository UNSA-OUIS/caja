<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotaDebitoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nota_debito', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->char('cui', 8)->nullable();
            $table->char('nues', 3);
            $table->string('serie', 4);
            $table->string('correlativo', 8);
            $table->integer('codigo_motivo');
            $table->string('descripcion_motivo');
            $table->decimal('total');
            $table->enum('estado', ['noEnviado', 'observado', 'rechazado', 'anulado', 'aceptado']);
            $table->tinyInteger('tipo_comprobante_id');
            $table->bigInteger('comprobante_id');
            $table->text('observaciones');
            $table->string('url_xml');
            $table->string('url_cdr');
            $table->string('url_pdf');
            $table->bigInteger('usuario_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('usuario_id')->references('id')->on('users')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('tipo_comprobante_id')->references('id')->on('tipo_comprobante')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('comprobante_id')->references('id')->on('comprobantes')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nota_debito');
    }
}
