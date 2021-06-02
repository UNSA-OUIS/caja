<?php

namespace Database\Seeders;

use App\Models\Comprobante;
use App\Models\DetallesComprobante;
use Illuminate\Database\Seeder;

class ComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comprobante::factory()->count(100)->has(DetallesComprobante::factory()->count(2), 'detalles')->create();
    }
}
