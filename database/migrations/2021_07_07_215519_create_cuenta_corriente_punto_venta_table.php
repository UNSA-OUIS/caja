<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuentaCorrientePuntoVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuenta_corriente_punto_venta', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('punto_venta_id');
            $table->bigInteger('cuenta_corriente_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('punto_venta_id')->references('id')->on('puntos_venta')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('cuenta_corriente_id')->references('id')->on('cuentas_corrientes')
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
        Schema::dropIfExists('cuenta_corriente_punto_venta');
    }
}
