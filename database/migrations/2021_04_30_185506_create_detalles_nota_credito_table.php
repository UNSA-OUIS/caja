<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesNotacreditoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_nota_credito', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->double('valor_unitario', 8, 4);
            $table->double('descuento', 8, 4);
            $table->boolean('estado')->default(true);
            $table->tinyInteger('concepto_id');
            $table->bigInteger('comprobante_id');
            $table->bigInteger('nota_credito_id');
            $table->timestamps();

            $table->foreign('concepto_id')->references('id')->on('conceptos')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('comprobante_id')->references('id')->on('comprobantes')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
                $table->foreign('nota_credito_id')->references('id')->on('nota_credito')
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
        Schema::dropIfExists('detalles_notacredito');
    }
}
