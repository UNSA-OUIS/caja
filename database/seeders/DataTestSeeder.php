<?php

namespace Database\Seeders;

use App\Models\Clasificador;
use App\Models\Comprobante;
use App\Models\Concepto;
use App\Models\CuentaCorriente;
use App\Models\Empresa;
use App\Models\Particular;
use App\Models\PuntosVenta;
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
        //
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


        Clasificador::create([ 'nombre'=>'FORMULARIOS']);
        Clasificador::create([ 'nombre'=>'CARNETS']);
        Clasificador::create([ 'nombre'=>'DERECHO DE EXAMEN DE ADMISION']);
        Clasificador::create([ 'nombre'=>'GRADOS, TITULOS, CONSTANCIAS Y CERTIFICADOS']);
        Clasificador::create([ 'nombre'=>'DERECHOS UNIVERSITARIOS']);
        Clasificador::create([ 'nombre'=>'REGISTROSR']);

        TiposConcepto::create(['id'=> '1','nombre'=>'ACADEMICO']);
        TiposConcepto::create(['id'=> '2','nombre'=>'MEDICINA']);
        TiposConcepto::create(['id'=> '3','nombre'=>'SERTEL']);
        TiposConcepto::create(['id'=> '4','nombre'=>'NO ACADEMICO']);

        Concepto::create([ 'codigo'=>'1','descripcion'=>'MATRICULA DE MAESTRIA O DOCTORADO','descripcion_imp'=>'MATRICULA MAEST. O DOCTO.','precio'=>'100','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create([ 'codigo'=>'2','descripcion'=>'CONSTANCIA DE MAESTRIA','descripcion_imp'=>'CONSTANCIA DE MAESTRIA','precio'=>'20','tipo_precio'=>'fijo','tipo_afectacion'=>'10','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create([ 'codigo'=>'3','descripcion'=>'CAMBIO DE APELLIDOS Y NOMBRE','descripcion_imp'=>'CAMBIO APELLIDOS/NOMBRES','precio'=>'','tipo_precio'=>'variable','tipo_afectacion'=>'10','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create([ 'codigo'=>'4','descripcion'=>'CONSTANCIA DE EGRESADO - MAESTRIA','descripcion_imp'=>'CONST.EGRES.MAES/DOC','precio'=>'80','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create([ 'codigo'=>'5','descripcion'=>'CERTIFICADO X SEMESTRE MAESTRIA/2DAS/RESID.MEDICO','descripcion_imp'=>'CERT. P/SEMESTRE MAES/2DA','precio'=>'200','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create([ 'codigo'=>'6','descripcion'=>'CERTIFICADO X ANO (MAS DE 10 ANOS)','descripcion_imp'=>'CERT. P/ANO(+ DE 10 ANOS)','precio'=>'25','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create([ 'codigo'=>'7','descripcion'=>'CERTIFICADO X ANO','descripcion_imp'=>'CERTIFICADO X ANO','precio'=>'11','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create([ 'codigo'=>'8','descripcion'=>'CONSTANCIA DE BACHILLER Y O PROFESIONAL','descripcion_imp'=>'CONSTANCIA DE BACHILLER Y O PROFESIONAL','precio'=>'30','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create([ 'codigo'=>'9','descripcion'=>'TITULO PROFES. X SUFICIENCIA - A. SOCIALES/BIOMED','descripcion_imp'=>'TITULO PROFES. X SUFICIENCIA - A. SOCIALES/BIOMED','precio'=>'75','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create([ 'codigo'=>'10','descripcion'=>'CARNET UNIV. DE POSGRADO','descripcion_imp'=>'CARNET UNIV. DE POSGRADO','precio'=>'100','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);



        // //TipoComprobante::create([]);
        // //Comprobante::create([]);
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


        // CuentaCorriente::create([]);
        DB::table('empresas')->truncate();
        Empresa::create(['ruc'=>'20549263675','razon_social'=>'RH ADUANAS S.A.C.','direccion'=>'---- CJN HUANAUCARE NRO. 295 INT. 403 LIMA LIMA SAN MIGUEL','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20532620580','razon_social'=>'LOGISTIC TRAVEL ILO S.A.C.','direccion'=>'MZA. B LOTE. 04 ASOC. F. BARRETO TACNA TACNA TACNA','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20100950180','razon_social'=>'AEROADUANA S.A.C. AGENTES AFIANZADOS DE ADUANA','direccion'=>'AV. ABEL B DU PETIT THOUARS NRO. 4655 INT. 503 URB. BARBONCITO LIMA LIMA MIRAFLORES','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20602195121','razon_social'=>'SOLUSOFT PERU S.A.C.','direccion'=>'AV. DE LA MARINA NRO. 1602 INT. 142- URB. COPERPERU LIMA LIMA PUEBLO LIBRE','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20390900407','razon_social'=>'MAGIC TECHNOLOGIES E.I.R.L.','direccion'=>'AV. ARENALES NRO. 659 LIMA LIMA LIMA','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20514449229','razon_social'=>'GROUP CORPORATION MULTI SYSTEM SOCIEDAD ANONIMA CERRADA','direccion'=>'AV. MEXICO NRO. 1829 LA VICTORIA LIMA LIMA LA VICTORIA','email'=>'jortiz@unsa.edu.pe']);
        Empresa::create(['ruc'=>'20331066703','razon_social'=>'INRETAIL PHARMA S.A.','direccion'=>'AV. DEFENSORES DEL MORRO NRO. 1277 LIMA LIMA CHORRILLOS','email'=>'jortiz@unsa.edu.pe']);
    
        //Asignación de conceptos por punto de venta
        $puntoVenta1 = PuntosVenta::find(1);
        $puntoVenta1->conceptos()->sync([1, 2, 3]);
        $puntoVenta2 = PuntosVenta::find(2);
        $puntoVenta2->conceptos()->sync([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $puntoVenta3 = PuntosVenta::find(3);
        $puntoVenta3->conceptos()->sync([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $puntoVenta4 = PuntosVenta::find(4);
        $puntoVenta4->conceptos()->sync([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $puntoVenta5 = PuntosVenta::find(5);
        $puntoVenta5->conceptos()->sync([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $puntoVenta6 = PuntosVenta::find(6);
        $puntoVenta6->conceptos()->sync([1, 2, 3]);
        $puntoVenta7 = PuntosVenta::find(7);
        $puntoVenta7->conceptos()->sync([1, 2, 3]);
    }
}
