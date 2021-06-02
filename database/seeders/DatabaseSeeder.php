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
        $this->call([DataTestSeeder::class]);
        $this->call([ComprobanteSeeder::class]);
        //$this->call([DetallesComprobanteSeeder::class]);
    }
}
