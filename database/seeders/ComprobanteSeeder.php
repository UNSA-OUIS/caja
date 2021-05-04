<?php

namespace Database\Seeders;

use App\Models\Comprobante;
use App\Models\TipoComprobante;
use Faker\Factory;
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
        /*DB::table('comprobantes')->insert(
            [
                'codigo' => Str::random(20),
                'cui' => '20143377',
                'nues' => '044',
                'serie' => 'F001',
                'correlativo' => '00000001',
                'total' => 0,
                'estado' => true,
            ],

        );*/

        $faker = Factory::create();
        TipoComprobante::create([
            'nombre'=> 'Factura'
        ]);

        for($i = 0; $i < 25; $i++) {
            Comprobante::create([
                'codigo' => $faker->randomNumber(9, false),
                'cui' => $faker->randomNumber(8, true),
                'nues' => $faker->randomNumber(3, true),
                'serie' => 'F'.$faker->randomNumber(3, false),
                'correlativo' => $faker->randomNumber(8, false),
                'total' => $faker->randomFloat(2, 10, 200),
                'estado' => 'noEnviado',
                'observaciones' => $faker->text(200),
                'url_xml' => $faker->url,
                'url_cdr' => $faker->url,
                'url_pdf' => $faker->url,
                'usuario_id' => 1,
                'tipo_comprobante_id' => 1,
            ]);
        }

        for($i = 0; $i < 25; $i++) {
            Comprobante::create([
                'codigo' => $faker->randomNumber(9, false),
                'cui' => $faker->randomNumber(8, true),
                'nues' => $faker->randomNumber(3, true),
                'serie' => 'F'.$faker->randomNumber(3, false),
                'correlativo' => $faker->randomNumber(8, false),
                'total' => $faker->randomFloat(2, 10, 200),
                'estado' => 'noEnviado',
                'observaciones' => $faker->text(200),
                'url_xml' => $faker->url,
                'url_cdr' => $faker->url,
                'url_pdf' => $faker->url,
                'usuario_id' => 2,
                'tipo_comprobante_id' => 1,
            ]);
        }

        for($i = 0; $i < 25; $i++) {
            Comprobante::create([
                'codigo' => $faker->randomNumber(9, false),
                'cui' => $faker->randomNumber(8, true),
                'nues' => $faker->randomNumber(3, true),
                'serie' => 'F'.$faker->randomNumber(3, false),
                'correlativo' => $faker->randomNumber(8, false),
                'total' => $faker->randomFloat(2, 10, 200),
                'estado' => 'noEnviado',
                'observaciones' => $faker->text(200),
                'url_xml' => $faker->url,
                'url_cdr' => $faker->url,
                'url_pdf' => $faker->url,
                'usuario_id' => 3,
                'tipo_comprobante_id' => 1,
            ]);
        }
    }
}
