<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetraccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detraccion', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('comprobante_id');
            $table->string('cuenta_detraccion');
            $table->integer('codigo_detraccion');
            $table->double('porcentaje_detraccion', 8, 4);
            $table->double('monto_detraccion', 8, 4);
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('detraccion');
    }
}
