<?php

namespace Database\Seeders;

use App\Models\Clasificador;
use Illuminate\Database\Seeder;

class ClasificadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Clasificador::create([ 'nombre'=>'FORMULARIOS']);
        Clasificador::create([ 'nombre'=>'CARNETS']);
        Clasificador::create([ 'nombre'=>'DERECHO DE EXAMEN DE ADMISION']);
        Clasificador::create([ 'nombre'=>'GRADOS, TITULOS, CONSTANCIAS Y CERTIFICADOS']);
        Clasificador::create([ 'nombre'=>'DERECHOS UNIVERSITARIOS']);
        Clasificador::create([ 'nombre'=>'REGISTROSR']);
    }
}
