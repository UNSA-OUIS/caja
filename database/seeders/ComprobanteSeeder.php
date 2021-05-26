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
        Comprobante::factory()->count(5000)->has(DetallesComprobante::factory()->count(1), 'detalles')->create();
    }
}
