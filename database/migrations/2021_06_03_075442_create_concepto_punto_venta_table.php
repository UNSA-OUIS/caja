<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptoPuntoVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concepto_punto_venta', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('punto_venta_id');
            $table->bigInteger('concepto_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('punto_venta_id')->references('id')->on('puntos_venta')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('concepto_id')->references('id')->on('conceptos')
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
        Schema::dropIfExists('concepto_punto_venta_');
    }
}
