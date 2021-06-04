<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->string('descripcion_imp', 50)->comment('Descripción corta para impresión de comprobante');
            $table->string('precio', 10)->nullable();
            $table->string('tipo_precio', 10);
            $table->string('tipo_afectacion', 25)->comment('Tipo de afectación según sunat');
            $table->tinyInteger('tipo_concepto_id');
            $table->bigInteger('clasificador_id');
            $table->tinyInteger('unidad_medida_id');
            $table->string('semestre');
            $table->string('codi_depe')->nullable()->comment("Codigo de la dependencia de la BD de sian en la tabla depe ");
            $table->boolean('estado')->default(true);
            $table->boolean('detraccion')->default(false);
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
            //$table->foreign('codi_depe')->references('codi_depe')->on(new Expression(DB::connection('mysql2')->getDatabaseName()). '.depe');
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
