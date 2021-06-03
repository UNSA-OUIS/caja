<?php

namespace Database\Seeders;

use App\Models\Particular;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticularSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('particulares')->truncate();

        Particular::create(['dni'=>'48611654','nombres'=>'TITA CRISTINA','apellidos'=>'CAMPAÑA NAVARRO','email'=>'tita@gmail.com']);
        Particular::create(['dni'=>'75665521','nombres'=>'ELVIS','apellidos'=>'SORIA CARRERA','email'=>'elvis@gmail.com']);
        Particular::create(['dni'=>'73568541','nombres'=>'DEYVIS JOEL','apellidos'=>'REMENTERIA VICUÑA','email'=>'deyvis@gmail.com']);
        Particular::create([ 'dni'=>'76854236','nombres'=>'EDILA','apellidos'=>'MORI QUEVEDO','email'=>'edila@gmail.com']);
        Particular::create([ 'dni'=>'43659852','nombres'=>'CARLOS ALBERTO','apellidos'=>'VERA PERTUZA','email'=>'carlos@gmail.com']);
        Particular::create([ 'dni'=>'47652598','nombres'=>'ADRIANA FIORELLA','apellidos'=>'GAMARRA VICOS','email'=>'adriana@gmail.com']);
        Particular::create([ 'dni'=>'47645598','nombres'=>'WENDY FLORMILA','apellidos'=>'MORALES VALVERDE','email'=>'wendy@gmail.com']);
        Particular::create([ 'dni'=>'45645598','nombres'=>'SIMION','apellidos'=>'SANGAMA SANGAMA','email'=>'simion@gmail.com']);
        Particular::create([ 'dni'=>'43987456','nombres'=>'LUISA','apellidos'=>'CCOYURI LIRA','email'=>'luisa@gmail.com']);
        Particular::create([ 'dni'=>'73654189','nombres'=>'ARIANA ALESSANDRA','apellidos'=>'CELIS ZEVALLOS','email'=>'ariana@gmail.com']);


    }
}
