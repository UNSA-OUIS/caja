<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_comprobante')->insert(
            [
                [
                    'nombre' => 'BOLETA DE VENTA',
                ],
                [
                    'nombre' => 'FACTURA',
                ],
                [
                    'nombre' => 'NOTA DE CREDITO',
                ],
                [
                    'nombre' => 'NOTA DE DEBITO',
                ],
            ],
            
        );
    }
}
