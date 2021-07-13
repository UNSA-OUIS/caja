<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVistaReciboIngreso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "CREATE VIEW vista_recibo_ingreso AS select conceptos.clasificador_id, conceptos.descripcion, MIN(comprobantes.cajero_id) as cajero_id,
            sum(detalles_comprobante.cantidad) as totalCobros, sum(detalles_comprobante.subtotal) as subtotal,
            comprobantes.created_at, comprobantes.tipo_pago, comprobantes.cuenta_corriente_id, comprobantes.recibo_ingreso_id from detalles_comprobante
            inner join conceptos on conceptos.id = detalles_comprobante.concepto_id
            inner join comprobantes on comprobantes.id = detalles_comprobante.comprobante_id
            group by conceptos.clasificador_id, conceptos.descripcion, comprobantes.created_at, comprobantes.tipo_pago,
            comprobantes.cuenta_corriente_id, comprobantes.recibo_ingreso_id;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW vista_recibo_ingreso");
    }
}
