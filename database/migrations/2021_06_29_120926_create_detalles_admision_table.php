<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesAdmisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_admision', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('concepto_id');
            $table->bigInteger('admision_id');
            $table->double('precio_variable')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('concepto_id')->references('id')->on('conceptos')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('admision_id')->references('id')->on('admision')
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
        Schema::dropIfExists('detalles_admision');
    }
}
