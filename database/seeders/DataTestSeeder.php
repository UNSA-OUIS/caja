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
        UnidadMedida::create(['id'=>'1','codigo'=>'4A','nombre'=>'BOBINAS']);
        UnidadMedida::create(['id'=>'2','codigo'=>'BJ','nombre'=>'BALDE']);
        UnidadMedida::create(['id'=>'3','codigo'=>'BLL','nombre'=>'BARRILES']);
        UnidadMedida::create(['id'=>'4','codigo'=>'BG','nombre'=>'BOLSA']);
        UnidadMedida::create(['id'=>'5','codigo'=>'BO','nombre'=>'BOTELLAS']);
        UnidadMedida::create(['id'=>'6','codigo'=>'BX','nombre'=>'CAJA']);
        UnidadMedida::create(['id'=>'7','codigo'=>'CT','nombre'=>'CARTONES']);
        UnidadMedida::create(['id'=>'8','codigo'=>'CMK','nombre'=>'CENTIMETRO CUADRADO']);
        UnidadMedida::create(['id'=>'9','codigo'=>'CMQ','nombre'=>'CENTIMETRO CUBICO']);
        UnidadMedida::create(['id'=>'10','codigo'=>'CMT','nombre'=>'CENTIMETRO LINEAL']);
        UnidadMedida::create(['id'=>'11','codigo'=>'CEN','nombre'=>'CIENTO DE UNIDADES']);
        UnidadMedida::create(['id'=>'12','codigo'=>'CY','nombre'=>'CILINDRO']);
        UnidadMedida::create(['id'=>'13','codigo'=>'CJ','nombre'=>'CONOS']);
        UnidadMedida::create(['id'=>'14','codigo'=>'DZN','nombre'=>'DOCENA']);
        UnidadMedida::create(['id'=>'15','codigo'=>'DZP','nombre'=>'DOCENA POR 10**6']);
        UnidadMedida::create(['id'=>'16','codigo'=>'BE','nombre'=>'FARDO']);
        UnidadMedida::create(['id'=>'17','codigo'=>'GLI','nombre'=>'GALON INGLES (4,545956L)']);
        UnidadMedida::create(['id'=>'18','codigo'=>'GRM','nombre'=>'GRAMO']);
        UnidadMedida::create(['id'=>'19','codigo'=>'GRO','nombre'=>'GRUESA']);
        UnidadMedida::create(['id'=>'20','codigo'=>'HLT','nombre'=>'HECTOLITRO']);
        UnidadMedida::create(['id'=>'21','codigo'=>'LEF','nombre'=>'HOJA']);
        UnidadMedida::create(['id'=>'22','codigo'=>'SET','nombre'=>'JUEGO']);
        UnidadMedida::create(['id'=>'23','codigo'=>'KGM','nombre'=>'KILOGRAMO']);
        UnidadMedida::create(['id'=>'24','codigo'=>'KTM','nombre'=>'KILOMETRO']);
        UnidadMedida::create(['id'=>'25','codigo'=>'KWH','nombre'=>'KILOVATIO HORA']);
        UnidadMedida::create(['id'=>'26','codigo'=>'KT','nombre'=>'KIT']);
        UnidadMedida::create(['id'=>'27','codigo'=>'CA','nombre'=>'LATAS']);
        UnidadMedida::create(['id'=>'28','codigo'=>'LBR','nombre'=>'LIBRAS']);
        UnidadMedida::create(['id'=>'29','codigo'=>'LTR','nombre'=>'LITRO']);
        UnidadMedida::create(['id'=>'30','codigo'=>'MWH','nombre'=>'MEGAWATT HORA']);
        UnidadMedida::create(['id'=>'31','codigo'=>'MTR','nombre'=>'METRO']);
        UnidadMedida::create(['id'=>'32','codigo'=>'MTK','nombre'=>'METRO CUADRADO']);
        UnidadMedida::create(['id'=>'33','codigo'=>'MTQ','nombre'=>'METRO CUBICO']);
        UnidadMedida::create(['id'=>'34','codigo'=>'MGM','nombre'=>'MILIGRAMOS']);
        UnidadMedida::create(['id'=>'35','codigo'=>'MLT','nombre'=>'MILILITRO']);
        UnidadMedida::create(['id'=>'36','codigo'=>'MMT','nombre'=>'MILIMETRO']);
        UnidadMedida::create(['id'=>'37','codigo'=>'MMK','nombre'=>'MILIMETRO CUADRADO']);
        UnidadMedida::create(['id'=>'38','codigo'=>'MMQ','nombre'=>'MILIMETRO CUBICO']);
        UnidadMedida::create(['id'=>'39','codigo'=>'MIL','nombre'=>'MILLARES']);
        UnidadMedida::create(['id'=>'40','codigo'=>'UM','nombre'=>'MILLON DE UNIDADES']);
        UnidadMedida::create(['id'=>'41','codigo'=>'ONZ','nombre'=>'ONZAS']);
        UnidadMedida::create(['id'=>'42','codigo'=>'PF','nombre'=>'PALETAS']);
        UnidadMedida::create(['id'=>'43','codigo'=>'PK','nombre'=>'PAQUETE']);
        UnidadMedida::create(['id'=>'44','codigo'=>'PR','nombre'=>'PAR']);
        UnidadMedida::create(['id'=>'45','codigo'=>'FOT','nombre'=>'PIES']);
        UnidadMedida::create(['id'=>'46','codigo'=>'FTK','nombre'=>'PIES CUADRADOS']);
        UnidadMedida::create(['id'=>'47','codigo'=>'FTQ','nombre'=>'PIES CUBICOS']);
        UnidadMedida::create(['id'=>'48','codigo'=>'C62','nombre'=>'PIEZAS']);
        UnidadMedida::create(['id'=>'49','codigo'=>'PG','nombre'=>'PLACAS']);
        UnidadMedida::create(['id'=>'50','codigo'=>'ST','nombre'=>'PLIEGO']);
        UnidadMedida::create(['id'=>'51','codigo'=>'INH','nombre'=>'PULGADAS']);
        UnidadMedida::create(['id'=>'52','codigo'=>'RM','nombre'=>'RESMA']);
        UnidadMedida::create(['id'=>'53','codigo'=>'DR','nombre'=>'TAMBOR']);
        UnidadMedida::create(['id'=>'54','codigo'=>'STN','nombre'=>'TONELADA CORTA']);
        UnidadMedida::create(['id'=>'55','codigo'=>'LTN','nombre'=>'TONELADA LARGA']);
        UnidadMedida::create(['id'=>'56','codigo'=>'TNE','nombre'=>'TONELADAS']);
        UnidadMedida::create(['id'=>'57','codigo'=>'TU','nombre'=>'TUBOS']);
        UnidadMedida::create(['id'=>'58','codigo'=>'NIU','nombre'=>'UNIDAD (BIENES)']);
        UnidadMedida::create(['id'=>'59','codigo'=>'ZZ','nombre'=>'UNIDAD (SERVICIOS)']);
        UnidadMedida::create(['id'=>'60','codigo'=>'GLL','nombre'=>'US GALON (3,7843 L)']);
        UnidadMedida::create(['id'=>'61','codigo'=>'YRD','nombre'=>'YARDA']);
        UnidadMedida::create(['id'=>'62','codigo'=>'YDK','nombre'=>'YARDA CUADRADA']);

        Clasificador::create(['id'=>'121013','nombre'=>'FORMULARIOS']);
        Clasificador::create(['id'=>'123001','nombre'=>'CARNETS']);
        Clasificador::create(['id'=>'123002','nombre'=>'DERECHO DE EXAMEN DE ADMISION']);
        Clasificador::create(['id'=>'1','nombre'=>'GRADOS, TITULOS, CONSTANCIAS Y CERTIFICADOS']);
        Clasificador::create(['id'=>'123004','nombre'=>'DERECHOS UNIVERSITARIOS']);
        Clasificador::create(['id'=>'123005','nombre'=>'REGISTROSR']);
        
        TiposConcepto::create(['id'=> '1','nombre'=>'ACADEMICO']);
        TiposConcepto::create(['id'=> '2','nombre'=>'MEDICINA']);
        TiposConcepto::create(['id'=> '3','nombre'=>'SERTEL']);
        TiposConcepto::create(['id'=> '4','nombre'=>'NO ACADEMICO']);
        
        Concepto::create(['id'=>'1','codigo'=>'1','descripcion'=>'MATRICULA DE MAESTRIA O DOCTORADO','descripcion_imp'=>'MATRICULA MAEST. O DOCTO.','precio'=>'100','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create(['id'=>'2','codigo'=>'2','descripcion'=>'CONSTANCIA DE MAESTRIA','descripcion_imp'=>'CONSTANCIA DE MAESTRIA','precio'=>'20','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create(['id'=>'3','codigo'=>'3','descripcion'=>'CAMBIO DE APELLIDOS Y NOMBRE','descripcion_imp'=>'CAMBIO APELLIDOS/NOMBRES','precio'=>'20','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create(['id'=>'4','codigo'=>'4','descripcion'=>'CONSTANCIA DE EGRESADO - MAESTRIA','descripcion_imp'=>'CONST.EGRES.MAES/DOC','precio'=>'80','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create(['id'=>'5','codigo'=>'5','descripcion'=>'CERTIFICADO X SEMESTRE MAESTRIA/2DAS/RESID.MEDICO','descripcion_imp'=>'CERT. P/SEMESTRE MAES/2DA','precio'=>'200','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create(['id'=>'6','codigo'=>'6','descripcion'=>'CERTIFICADO X ANO (MAS DE 10 ANOS)','descripcion_imp'=>'CERT. P/ANO(+ DE 10 ANOS)','precio'=>'25','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create(['id'=>'7','codigo'=>'7','descripcion'=>'CERTIFICADO X ANO','descripcion_imp'=>'CERTIFICADO X ANO','precio'=>'11','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create(['id'=>'8','codigo'=>'8','descripcion'=>'CONSTANCIA DE BACHILLER Y O PROFESIONAL','descripcion_imp'=>'CONSTANCIA DE BACHILLER Y O PROFESIONAL','precio'=>'30','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create(['id'=>'9','codigo'=>'9','descripcion'=>'TITULO PROFES. X SUFICIENCIA - A. SOCIALES/BIOMED','descripcion_imp'=>'TITULO PROFES. X SUFICIENCIA - A. SOCIALES/BIOMED','precio'=>'75','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create(['id'=>'10','codigo'=>'10','descripcion'=>'CARNET UNIV. DE POSGRADO','descripcion_imp'=>'CARNET UNIV. DE POSGRADO','precio'=>'100','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>'1','unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        
        
        
        // //TipoComprobante::create([]);
        // //Comprobante::create([]);
        Particular::create(['id'=>'1','dni'=>'48611654','nombres'=>'TITA CRISTINA','apellidos'=>'CAMPAÑA NAVARRO','email'=>'tita@gmail.com']);
        Particular::create(['id'=>'2','dni'=>'75665521','nombres'=>'ELVIS','apellidos'=>'SORIA CARRERA','email'=>'elvis@gmail.com']);
        Particular::create(['id'=>'3','dni'=>'73568541','nombres'=>'DEYVIS JOEL','apellidos'=>'REMENTERIA VICUÑA','email'=>'deyvis@gmail.com']);
        Particular::create(['id'=>'4','dni'=>'76854236','nombres'=>'EDILA','apellidos'=>'MORI QUEVEDO','email'=>'edila@gmail.com']);
        Particular::create(['id'=>'5','dni'=>'43659852','nombres'=>'CARLOS ALBERTO','apellidos'=>'VERA PERTUZA','email'=>'carlos@gmail.com']);
        Particular::create(['id'=>'6','dni'=>'47652598','nombres'=>'ADRIANA FIORELLA','apellidos'=>'GAMARRA VICOS','email'=>'adriana@gmail.com']);
        Particular::create(['id'=>'7','dni'=>'47645598','nombres'=>'WENDY FLORMILA','apellidos'=>'MORALES VALVERDE','email'=>'wendy@gmail.com']);
        Particular::create(['id'=>'8','dni'=>'45645598','nombres'=>'SIMION','apellidos'=>'SANGAMA SANGAMA','email'=>'simion@gmail.com']);
        Particular::create(['id'=>'9','dni'=>'43987456','nombres'=>'LUISA','apellidos'=>'CCOYURI LIRA','email'=>'luisa@gmail.com']);
        Particular::create(['id'=>'10','dni'=>'73654189','nombres'=>'ARIANA ALESSANDRA','apellidos'=>'CELIS ZEVALLOS','email'=>'ariana@gmail.com']);
        Particular::create(['id'=>'11','dni'=>'48611309','nombres'=>'EDSON VICTOR','apellidos'=>'LIPA URBINA','email'=>'elipau@gmail.com']);
        
        // CuentaCorriente::create([]);
        Empresa::create(['id'=>'1','ruc'=>'20549263675','razon_social'=>'RH ADUANAS S.A.C.','direccion'=>'---- CJN HUANAUCARE NRO. 295 INT. 403 LIMA LIMA SAN MIGUEL','email'=>'huam@gmail.com']);
        Empresa::create(['id'=>'2','ruc'=>'20532620580','razon_social'=>'LOGISTIC TRAVEL ILO S.A.C.','direccion'=>'MZA. B LOTE. 04 ASOC. F. BARRETO TACNA TACNA TACNA','email'=>'tacna@gmail.com']);
        Empresa::create(['id'=>'3','ruc'=>'20100950180','razon_social'=>'AEROADUANA S.A.C. AGENTES AFIANZADOS DE ADUANA','direccion'=>'AV. ABEL B DU PETIT THOUARS NRO. 4655 INT. 503 URB. BARBONCITO LIMA LIMA MIRAFLORES','email'=>'agentes@gmail.com']);
        Empresa::create(['id'=>'4','ruc'=>'20602195121','razon_social'=>'SOLUSOFT PERU S.A.C.','direccion'=>'AV. DE LA MARINA NRO. 1602 INT. 142- URB. COPERPERU LIMA LIMA PUEBLO LIBRE','email'=>'aolusoft@gmail.com']);
        Empresa::create(['id'=>'5','ruc'=>'20390900407','razon_social'=>'MAGIC TECHNOLOGIES E.I.R.L.','direccion'=>'AV. ARENALES NRO. 659 LIMA LIMA LIMA','email'=>'magic@gmail.com']);
        Empresa::create(['id'=>'6','ruc'=>'20514449229','razon_social'=>'GROUP CORPORATION MULTI SYSTEM SOCIEDAD ANONIMA CERRADA','direccion'=>'AV. MEXICO NRO. 1829 LA VICTORIA LIMA LIMA LA VICTORIA','email'=>'group@gmail.com']);
        Empresa::create(['id'=>'7','ruc'=>'20331066703','razon_social'=>'INRETAIL PHARMA S.A.','direccion'=>'AV. DEFENSORES DEL MORRO NRO. 1277 LIMA LIMA CHORRILLOS','email'=>'inkafarma@gmail.com']);
    }
}
