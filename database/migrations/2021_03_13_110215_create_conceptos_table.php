<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conceptos', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo')->unique();
            $table->string('descripcion', 50);
            $table->string('descripcion_imp', 25)->comment('Descripción corta para impresión de comprobante');
            $table->string('precio', 10);
            $table->string('tipo_afectacion', 25)->comment('Tipo de afectación según sunat');
            $table->tinyInteger('tipo_concepto_id');            
            $table->tinyInteger('clasificador_id');            
            $table->tinyInteger('unidad_medida_id');            
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('tipo_concepto_id')->references('id')->on('tipos_concepto')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('clasificador_id')->references('id')->on('clasificadores')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('unidad_medida_id')->references('id')->on('unidades_medida')
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
        Schema::dropIfExists('conceptos');
    }
}
