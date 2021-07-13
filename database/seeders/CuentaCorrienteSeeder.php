<?php

namespace Database\Seeders;

use App\Models\CuentaCorriente;
use Illuminate\Database\Seeder;

class CuentaCorrienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CuentaCorriente::create(['numero_cuenta' => '21503649244100', 'banco' => 'BCP', 'moneda' => 'PEN']);
        CuentaCorriente::create(['numero_cuenta' => '215-1551-021', 'banco' => 'BNAC', 'moneda' => 'USD']);
        CuentaCorriente::create(['numero_cuenta' => '21503649789145', 'banco' => 'BCP', 'moneda' => 'PEN']);
        
    }
}
