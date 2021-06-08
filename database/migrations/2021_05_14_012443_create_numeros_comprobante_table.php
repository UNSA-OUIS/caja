<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNumerosComprobanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numeros_comprobante', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('tipo_comprobante_id');
            $table->string('serie', 4)->unique();
            $table->string('correlativo', 8);
            $table->bigInteger('punto_venta_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tipo_comprobante_id')->references('id')->on('tipo_comprobante')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('punto_venta_id')->references('id')->on('puntos_venta')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['punto_venta_id', 'tipo_comprobante_id', 'serie']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('numeros_operacion');
    }
}
