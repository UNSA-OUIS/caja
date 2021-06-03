<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('empresas')->truncate();
        Empresa::create(['ruc'=>'20549263675','razon_social'=>'RH ADUANAS S.A.C.','direccion'=>'---- CJN HUANAUCARE NRO. 295 INT. 403 LIMA LIMA SAN MIGUEL','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20532620580','razon_social'=>'LOGISTIC TRAVEL ILO S.A.C.','direccion'=>'MZA. B LOTE. 04 ASOC. F. BARRETO TACNA TACNA TACNA','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20100950180','razon_social'=>'AEROADUANA S.A.C. AGENTES AFIANZADOS DE ADUANA','direccion'=>'AV. ABEL B DU PETIT THOUARS NRO. 4655 INT. 503 URB. BARBONCITO LIMA LIMA MIRAFLORES','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20602195121','razon_social'=>'SOLUSOFT PERU S.A.C.','direccion'=>'AV. DE LA MARINA NRO. 1602 INT. 142- URB. COPERPERU LIMA LIMA PUEBLO LIBRE','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20390900407','razon_social'=>'MAGIC TECHNOLOGIES E.I.R.L.','direccion'=>'AV. ARENALES NRO. 659 LIMA LIMA LIMA','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20514449229','razon_social'=>'GROUP CORPORATION MULTI SYSTEM SOCIEDAD ANONIMA CERRADA','direccion'=>'AV. MEXICO NRO. 1829 LA VICTORIA LIMA LIMA LA VICTORIA','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20331066703','razon_social'=>'INRETAIL PHARMA S.A.','direccion'=>'AV. DEFENSORES DEL MORRO NRO. 1277 LIMA LIMA CHORRILLOS','email'=>'jortiz@unsa.edu.pe']);
    }
}
