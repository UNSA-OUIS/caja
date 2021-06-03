<?php

namespace Database\Seeders;

use App\Models\Clasificador;
use App\Models\Comprobante;
use App\Models\Concepto;
use App\Models\CuentaCorriente;
use App\Models\Empresa;
use App\Models\Particular;
use App\Models\TipoComprobante;
use App\Models\TiposConcepto;
use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /*Clasificador::create([ 'nombre'=>'FORMULARIOS']);
        Clasificador::create([ 'nombre'=>'CARNETS']);
        Clasificador::create([ 'nombre'=>'DERECHO DE EXAMEN DE ADMISION']);
        Clasificador::create([ 'nombre'=>'GRADOS, TITULOS, CONSTANCIAS Y CERTIFICADOS']);
        Clasificador::create([ 'nombre'=>'DERECHOS UNIVERSITARIOS']);
        Clasificador::create([ 'nombre'=>'REGISTROSR']);*/







        // //TipoComprobante::create([]);
        // //Comprobante::create([]);
        /*DB::table('particulares')->truncate();

        Particular::create(['dni'=>'48611654','nombres'=>'TITA CRISTINA','apellidos'=>'CAMPAÑA NAVARRO','email'=>'tita@gmail.com']);
        Particular::create(['dni'=>'75665521','nombres'=>'ELVIS','apellidos'=>'SORIA CARRERA','email'=>'elvis@gmail.com']);
        Particular::create(['dni'=>'73568541','nombres'=>'DEYVIS JOEL','apellidos'=>'REMENTERIA VICUÑA','email'=>'deyvis@gmail.com']);
        Particular::create([ 'dni'=>'76854236','nombres'=>'EDILA','apellidos'=>'MORI QUEVEDO','email'=>'edila@gmail.com']);
        Particular::create([ 'dni'=>'43659852','nombres'=>'CARLOS ALBERTO','apellidos'=>'VERA PERTUZA','email'=>'carlos@gmail.com']);
        Particular::create([ 'dni'=>'47652598','nombres'=>'ADRIANA FIORELLA','apellidos'=>'GAMARRA VICOS','email'=>'adriana@gmail.com']);
        Particular::create([ 'dni'=>'47645598','nombres'=>'WENDY FLORMILA','apellidos'=>'MORALES VALVERDE','email'=>'wendy@gmail.com']);
        Particular::create([ 'dni'=>'45645598','nombres'=>'SIMION','apellidos'=>'SANGAMA SANGAMA','email'=>'simion@gmail.com']);
        Particular::create([ 'dni'=>'43987456','nombres'=>'LUISA','apellidos'=>'CCOYURI LIRA','email'=>'luisa@gmail.com']);
        Particular::create([ 'dni'=>'73654189','nombres'=>'ARIANA ALESSANDRA','apellidos'=>'CELIS ZEVALLOS','email'=>'ariana@gmail.com']);*/


        // CuentaCorriente::create([]);
        /*DB::table('empresas')->truncate();
        Empresa::create(['ruc'=>'20549263675','razon_social'=>'RH ADUANAS S.A.C.','direccion'=>'---- CJN HUANAUCARE NRO. 295 INT. 403 LIMA LIMA SAN MIGUEL','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20532620580','razon_social'=>'LOGISTIC TRAVEL ILO S.A.C.','direccion'=>'MZA. B LOTE. 04 ASOC. F. BARRETO TACNA TACNA TACNA','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20100950180','razon_social'=>'AEROADUANA S.A.C. AGENTES AFIANZADOS DE ADUANA','direccion'=>'AV. ABEL B DU PETIT THOUARS NRO. 4655 INT. 503 URB. BARBONCITO LIMA LIMA MIRAFLORES','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20602195121','razon_social'=>'SOLUSOFT PERU S.A.C.','direccion'=>'AV. DE LA MARINA NRO. 1602 INT. 142- URB. COPERPERU LIMA LIMA PUEBLO LIBRE','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20390900407','razon_social'=>'MAGIC TECHNOLOGIES E.I.R.L.','direccion'=>'AV. ARENALES NRO. 659 LIMA LIMA LIMA','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20514449229','razon_social'=>'GROUP CORPORATION MULTI SYSTEM SOCIEDAD ANONIMA CERRADA','direccion'=>'AV. MEXICO NRO. 1829 LA VICTORIA LIMA LIMA LA VICTORIA','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20331066703','razon_social'=>'INRETAIL PHARMA S.A.','direccion'=>'AV. DEFENSORES DEL MORRO NRO. 1277 LIMA LIMA CHORRILLOS','email'=>'jortiz@unsa.edu.pe']);*/
    }
}
