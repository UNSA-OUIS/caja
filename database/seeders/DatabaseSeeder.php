<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([PermisoSeeder::class]);
        $this->call([UnidadMedidaSeeder::class]);
        $this->call([TiposConceptoSeeder::class]);
        $this->call([ParticularSeeder::class]);
        $this->call([ClasificadorSeeder::class]);
        $this->call([EmpresaSeeder::class]);
        $this->call([ConceptoSeeder::class]);
        $this->call([AdmisionSeeder::class]);
        $this->call([CuentaCorrienteSeeder::class]);
        //$this->call([DataTestSeeder::class]);
        //$this->call([ComprobanteSeeder::class]);
        //$this->call([DetallesComprobanteSeeder::class]);
    }
}
