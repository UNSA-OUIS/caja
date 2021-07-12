<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecibosIngresoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recibos_ingreso', function (Blueprint $table) {
            $table->id();
            $table->string('correlativo')->unique();
            $table->dateTime('fecha_registro');
            $table->bigInteger('cajero_id');
            $table->tinyInteger('cuenta_corriente_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cajero_id')->references('id')->on('users')
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
        Schema::dropIfExists('recibos_ingreso');
    }
}
