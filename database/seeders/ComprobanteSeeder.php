<?php

namespace Database\Seeders;

use App\Models\Comprobante;
use App\Models\DetallesComprobante;
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
                'nro_recibo' => Str::random(20),
                'codi_usuario' => '20143377',
                'nues_espe' => '044',
                'serie' => 'F001',
                'correlativo' => '00000001',
                'total' => 0,
                'estado' => true,
            ],

        );*/

        $faker = Factory::create();
        

        for($i = 0; $i < 25; $i++) {
            Comprobante::create([
                'nro_recibo' => $faker->randomNumber(9, false),
                'tipo_usuario' => 'alumno',
                'codi_usuario' => $faker->randomNumber(8, true),
                'nues_espe' => $faker->randomNumber(3, true),
                'serie' => 'F'.$faker->randomNumber(3, false),
                'correlativo' => $faker->randomNumber(8, false),
                'total' => $faker->randomFloat(2, 10, 200),
                'total_descuento' => $faker->randomFloat(2, 10, 200),
                'total_impuesto' => $faker->randomFloat(2, 10, 200),
                'estado' => 'noEnviado',
                'observaciones' => $faker->text(200),
                'url_xml' => $faker->url,
                'url_cdr' => $faker->url,
                'url_pdf' => $faker->url,
                'cajero_id' => 1,
                'tipo_comprobante_id' => 1,
            ]);
        }

        for($i = 0; $i < 25; $i++) {
            Comprobante::create([
                'nro_recibo' => $faker->randomNumber(9, false),
                'tipo_usuario' => 'alumno',
                'codi_usuario' => $faker->randomNumber(8, true),
                'nues_espe' => $faker->randomNumber(3, true),
                'serie' => 'F'.$faker->randomNumber(3, false),
                'correlativo' => $faker->randomNumber(8, false),
                'total' => $faker->randomFloat(2, 10, 200),
                'total_descuento' => $faker->randomFloat(2, 10, 200),
                'total_impuesto' => $faker->randomFloat(2, 10, 200),
                'estado' => 'noEnviado',
                'observaciones' => $faker->text(200),
                'url_xml' => $faker->url,
                'url_cdr' => $faker->url,
                'url_pdf' => $faker->url,
                'cajero_id' => 2,
                'tipo_comprobante_id' => 1,
            ]);
        }

        for($i = 0; $i < 25; $i++) {
            Comprobante::create([
                'nro_recibo' => $faker->randomNumber(9, false),
                'tipo_usuario' => 'alumno',
                'codi_usuario' => $faker->randomNumber(8, true),
                'nues_espe' => $faker->randomNumber(3, true),
                'serie' => 'F'.$faker->randomNumber(3, false),
                'correlativo' => $faker->randomNumber(8, false),
                'total' => $faker->randomFloat(2, 10, 200),
                'total_descuento' => $faker->randomFloat(2, 10, 200),
                'total_impuesto' => $faker->randomFloat(2, 10, 200),
                'estado' => 'anulado',
                'observaciones' => $faker->text(200),
                'url_xml' => $faker->url,
                'url_cdr' => $faker->url,
                'url_pdf' => $faker->url,
                'cajero_id' => 3,
                'tipo_comprobante_id' => 1,
            ]);
        }

        DetallesComprobante::create([
            'cantidad' => 1,
            'valor_unitario' => 10,
            'descuento' => 1,
            'concepto_id' => 1,
            'comprobante_id' => 1,
        ]);
        DetallesComprobante::create([
            'cantidad' => 1,
            'valor_unitario' => 10,
            'descuento' => 1,
            'concepto_id' => 1,
            'comprobante_id' => 1,
        ]);
    }
}
