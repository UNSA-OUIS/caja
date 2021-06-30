<?php

namespace Database\Seeders;

use App\Models\Concepto;
use App\Models\PuntosVenta;
use Illuminate\Database\Seeder;

class ConceptoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Concepto::create([ 'codigo'=>'1','descripcion'=>'MATRICULA DE MAESTRIA O DOCTORADO','descripcion_imp'=>'MATRICULA MAEST. O DOCTO.','precio'=>'100','tipo_precio'=>'fijo','tipo_afectacion'=>'30','tipo_concepto_id'=>'1','clasificador_id'=>1,'unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create([ 'codigo'=>'2','descripcion'=>'CONSTANCIA DE MAESTRIA','descripcion_imp'=>'CONSTANCIA DE MAESTRIA','precio'=>'20','tipo_precio'=>'fijo','tipo_afectacion'=>'10','tipo_concepto_id'=>'1','clasificador_id'=>2,'unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create([ 'codigo'=>'3','descripcion'=>'CAMBIO DE APELLIDOS Y NOMBRE','descripcion_imp'=>'CAMBIO APELLIDOS/NOMBRES','precio'=>'','tipo_precio'=>'variable','tipo_afectacion'=>'10','tipo_concepto_id'=>'1','clasificador_id'=>3,'unidad_medida_id'=>'59','semestre'=>'1','codi_depe'=>'306151900','estado'=>'true','detraccion'=>'false']);
        Concepto::create(['codigo' => '4', 'descripcion' => 'CONSTANCIA DE EGRESADO - MAESTRIA', 'descripcion_imp' => 'CONST.EGRES.MAES/DOC', 'precio' => '80', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 4, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '5', 'descripcion' => 'CERTIFICADO X SEMESTRE MAESTRIA/2DAS/RESID.MEDICO', 'descripcion_imp' => 'CERT. P/SEMESTRE MAES/2DA', 'precio' => '200', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 5, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '6', 'descripcion' => 'CERTIFICADO X ANO (MAS DE 10 ANOS)', 'descripcion_imp' => 'CERT. P/ANO(+ DE 10 ANOS)', 'precio' => '25', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 6, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '7', 'descripcion' => 'CERTIFICADO X ANO', 'descripcion_imp' => 'CERTIFICADO X ANO', 'precio' => '11', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 1, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '8', 'descripcion' => 'CONSTANCIA DE BACHILLER Y O PROFESIONAL', 'descripcion_imp' => 'CONSTANCIA DE BACHILLER Y O PROFESIONAL', 'precio' => '30', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 2, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '9', 'descripcion' => 'TITULO PROFES. X SUFICIENCIA - A. SOCIALES/BIOMED', 'descripcion_imp' => 'TITULO PROFES. X SUFICIENCIA - A. SOCIALES/BIOMED', 'precio' => '75', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '10', 'descripcion' => 'CARNET UNIV. DE POSGRADO', 'descripcion_imp' => 'CARNET UNIV. DE POSGRADO', 'precio' => '100', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 4, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);

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
