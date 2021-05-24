<?php

namespace Database\Seeders;

use App\Models\DetallesComprobante;
use Illuminate\Database\Seeder;

class DetallesComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DetallesComprobante::factory()->count(50)->create();
    }
}
