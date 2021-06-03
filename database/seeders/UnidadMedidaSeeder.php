<?php

namespace Database\Seeders;

use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;

class UnidadMedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UnidadMedida::create(['codigo'=>'4A','nombre'=>'BOBINAS']);
        UnidadMedida::create(['codigo'=>'BJ','nombre'=>'BALDE']);
        UnidadMedida::create(['codigo'=>'BLL','nombre'=>'BARRILES']);
        UnidadMedida::create(['codigo'=>'BG','nombre'=>'BOLSA']);
        UnidadMedida::create(['codigo'=>'BO','nombre'=>'BOTELLAS']);
        UnidadMedida::create(['codigo'=>'BX','nombre'=>'CAJA']);
        UnidadMedida::create(['codigo'=>'CT','nombre'=>'CARTONES']);
        UnidadMedida::create(['codigo'=>'CMK','nombre'=>'CENTIMETRO CUADRADO']);
        UnidadMedida::create(['codigo'=>'CMQ','nombre'=>'CENTIMETRO CUBICO']);
        UnidadMedida::create(['codigo'=>'CMT','nombre'=>'CENTIMETRO LINEAL']);
        UnidadMedida::create(['codigo'=>'CEN','nombre'=>'CIENTO DE UNIDADES']);
        UnidadMedida::create(['codigo'=>'CY','nombre'=>'CILINDRO']);
        UnidadMedida::create(['codigo'=>'CJ','nombre'=>'CONOS']);
        UnidadMedida::create(['codigo'=>'DZN','nombre'=>'DOCENA']);
        UnidadMedida::create(['codigo'=>'DZP','nombre'=>'DOCENA POR 10**6']);
        UnidadMedida::create(['codigo'=>'BE','nombre'=>'FARDO']);
        UnidadMedida::create(['codigo'=>'GLI','nombre'=>'GALON INGLES (4,545956L)']);
        UnidadMedida::create(['codigo'=>'GRM','nombre'=>'GRAMO']);
        UnidadMedida::create(['codigo'=>'GRO','nombre'=>'GRUESA']);
        UnidadMedida::create(['codigo'=>'HLT','nombre'=>'HECTOLITRO']);
        UnidadMedida::create(['codigo'=>'LEF','nombre'=>'HOJA']);
        UnidadMedida::create(['codigo'=>'SET','nombre'=>'JUEGO']);
        UnidadMedida::create(['codigo'=>'KGM','nombre'=>'KILOGRAMO']);
        UnidadMedida::create(['codigo'=>'KTM','nombre'=>'KILOMETRO']);
        UnidadMedida::create(['codigo'=>'KWH','nombre'=>'KILOVATIO HORA']);
        UnidadMedida::create(['codigo'=>'KT','nombre'=>'KIT']);
        UnidadMedida::create(['codigo'=>'CA','nombre'=>'LATAS']);
        UnidadMedida::create(['codigo'=>'LBR','nombre'=>'LIBRAS']);
        UnidadMedida::create(['codigo'=>'LTR','nombre'=>'LITRO']);
        UnidadMedida::create(['codigo'=>'MWH','nombre'=>'MEGAWATT HORA']);
        UnidadMedida::create(['codigo'=>'MTR','nombre'=>'METRO']);
        UnidadMedida::create(['codigo'=>'MTK','nombre'=>'METRO CUADRADO']);
        UnidadMedida::create(['codigo'=>'MTQ','nombre'=>'METRO CUBICO']);
        UnidadMedida::create(['codigo'=>'MGM','nombre'=>'MILIGRAMOS']);
        UnidadMedida::create(['codigo'=>'MLT','nombre'=>'MILILITRO']);
        UnidadMedida::create(['codigo'=>'MMT','nombre'=>'MILIMETRO']);
        UnidadMedida::create(['codigo'=>'MMK','nombre'=>'MILIMETRO CUADRADO']);
        UnidadMedida::create(['codigo'=>'MMQ','nombre'=>'MILIMETRO CUBICO']);
        UnidadMedida::create(['codigo'=>'MIL','nombre'=>'MILLARES']);
        UnidadMedida::create(['codigo'=>'UM','nombre'=>'MILLON DE UNIDADES']);
        UnidadMedida::create(['codigo'=>'ONZ','nombre'=>'ONZAS']);
        UnidadMedida::create(['codigo'=>'PF','nombre'=>'PALETAS']);
        UnidadMedida::create(['codigo'=>'PK','nombre'=>'PAQUETE']);
        UnidadMedida::create(['codigo'=>'PR','nombre'=>'PAR']);
        UnidadMedida::create(['codigo'=>'FOT','nombre'=>'PIES']);
        UnidadMedida::create(['codigo'=>'FTK','nombre'=>'PIES CUADRADOS']);
        UnidadMedida::create(['codigo'=>'FTQ','nombre'=>'PIES CUBICOS']);
        UnidadMedida::create(['codigo'=>'C62','nombre'=>'PIEZAS']);
        UnidadMedida::create(['codigo'=>'PG','nombre'=>'PLACAS']);
        UnidadMedida::create(['codigo'=>'ST','nombre'=>'PLIEGO']);
        UnidadMedida::create(['codigo'=>'INH','nombre'=>'PULGADAS']);
        UnidadMedida::create(['codigo'=>'RM','nombre'=>'RESMA']);
        UnidadMedida::create(['codigo'=>'DR','nombre'=>'TAMBOR']);
        UnidadMedida::create(['codigo'=>'STN','nombre'=>'TONELADA CORTA']);
        UnidadMedida::create(['codigo'=>'LTN','nombre'=>'TONELADA LARGA']);
        UnidadMedida::create(['codigo'=>'TNE','nombre'=>'TONELADAS']);
        UnidadMedida::create(['codigo'=>'TU','nombre'=>'TUBOS']);
        UnidadMedida::create(['codigo'=>'NIU','nombre'=>'UNIDAD (BIENES)']);
        UnidadMedida::create(['codigo'=>'ZZ','nombre'=>'UNIDAD (SERVICIOS)']);
        UnidadMedida::create(['codigo'=>'GLL','nombre'=>'US GALON (3,7843 L)']);
        UnidadMedida::create(['codigo'=>'YRD','nombre'=>'YARDA']);
        UnidadMedida::create(['codigo'=>'YDK','nombre'=>'YARDA CUADRADA']);
    }
}
