<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesComprobanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_comprobante', function (Blueprint $table) {
            $table->id();            
            $table->integer('cantidad');
            $table->double('valor_unitario', 8, 4);            
            $table->double('descuento', 8, 4);    
            $table->boolean('estado');
            $table->tinyInteger('concepto_id');            
            $table->bigInteger('comprobante_id');
            $table->timestamps();

            $table->foreign('concepto_id')->references('id')->on('conceptos')
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
        Schema::dropIfExists('detalles_comprobante');
    }
}
