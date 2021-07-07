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
            $table->double('gravado', 8, 4);
            $table->double('inafecto', 8, 4);
            $table->double('impuesto', 8, 4);
            $table->double('descuento', 8, 4)->default(0.0);
            $table->string('resolucion')->nullable();
            $table->boolean('estado')->default(true);
            $table->enum('tipo_descuento', ['soles', 'porcentaje']);
            $table->string('codi_depe')->comment("Codigo de la dependencia de la BD de sian en la tabla depe ");
            $table->decimal('subtotal', 7, 2);
            $table->tinyInteger('concepto_id');
            $table->bigInteger('clasificador_id');
            $table->bigInteger('comprobante_id');
            $table->timestamps();

            $table->foreign('concepto_id')->references('id')->on('conceptos')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('clasificador_id')->references('id')->on('clasificadores')
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
