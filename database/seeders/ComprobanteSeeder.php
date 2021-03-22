<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comprobantes')->insert(
            [
                'codigo' => Str::random(20),
                'cui' => '20143377',
                'nues' => '044',
                'serie' => 'F001',
                'correlativo' => '00000001',
                'total' => 0,
                'estado' => true,
            ],

        );
    }
}
