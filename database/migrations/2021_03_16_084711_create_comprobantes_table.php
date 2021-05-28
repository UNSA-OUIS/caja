<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->string('nro_operacion')->unique()->nullable();
            $table->enum('tipo_usuario', ['alumno', 'docente', 'dependencia', 'particular', 'empresa']);
            $table->string('codi_usuario', 20)->nullable();
            $table->char('nues_espe', 3)->nullable();
            $table->tinyInteger('tipo_comprobante_id');
            $table->tinyInteger('tipo_comprobante_afectado')->nullable();
            $table->string('serie', 4);
            $table->string('correlativo', 8);
            $table->decimal('total_descuento');
            $table->decimal('total_impuesto');
            $table->decimal('total');
            $table->enum('estado', ['noEnviado', 'observado', 'rechazado', 'anulado', 'aceptado']);
            $table->string('email', 100)->nullable();
            $table->text('observaciones')->nullable();
            $table->string('url_xml')->nullable();
            $table->string('url_cdr')->nullable();
            $table->string('url_pdf')->nullable();
            $table->string('url_ticket')->nullable();
            $table->boolean('cancelado')->nullable()->default(false);
            $table->bigInteger('cajero_id');
            $table->char('tipo_nota', 2)->nullable();
            $table->text('motivo')->nullable();
            $table->string('serie_afectada', 4)->nullable();
            $table->string('correlativo_afectado', 8)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cajero_id')->references('id')->on('users')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('tipo_comprobante_id')->references('id')->on('tipo_comprobante')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('tipo_comprobante_afectado')->references('id')->on('tipo_comprobante')
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
        Schema::dropIfExists('comprobantes');
    }
}
