<?php

namespace Database\Seeders;

use App\Models\TiposConcepto;
use Illuminate\Database\Seeder;

class TiposConceptoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TiposConcepto::create(['id'=> '1','nombre'=>'ACADEMICO']);
        TiposConcepto::create(['id'=> '2','nombre'=>'MEDICINA']);
        TiposConcepto::create(['id'=> '3','nombre'=>'SERTEL']);
        TiposConcepto::create(['id'=> '4','nombre'=>'NO ACADEMICO']);
    }
}
