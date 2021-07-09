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

        Concepto::create(['id' => '1', 'codigo' => '1', 'descripcion' => 'MATRICULA DE MAESTRIA O DOCTORADO', 'descripcion_imp' => 'MATRICULA MAEST. O DOCTO.', 'precio' => '100', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 1, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '2', 'codigo' => '2', 'descripcion' => 'CONSTANCIA DE MAESTRIA', 'descripcion_imp' => 'CONSTANCIA DE MAESTRIA', 'precio' => '20', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '10', 'tipo_concepto_id' => '1', 'clasificador_id' => 2, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '3', 'codigo' => '3', 'descripcion' => 'CAMBIO DE APELLIDOS Y NOMBRE', 'descripcion_imp' => 'CAMBIO APELLIDOS/NOMBRES', 'precio' => '', 'tipo_precio' => 'variable', 'tipo_afectacion' => '10', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '14', 'codigo' => '14', 'descripcion' => 'CONSTANCIA DE EGRESADO - MAESTRIA', 'descripcion_imp' => 'CONST.EGRES.MAES/DOC', 'precio' => '80', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 4, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '5', 'codigo' => '5', 'descripcion' => 'CERTIFICADO X SEMESTRE MAESTRIA/2DAS/RESID.MEDICO', 'descripcion_imp' => 'CERT. P/SEMESTRE MAES/2DA', 'precio' => '200', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 5, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '6', 'codigo' => '6', 'descripcion' => 'CERTIFICADO X ANO (MAS DE 10 ANOS)', 'descripcion_imp' => 'CERT. P/ANO(+ DE 10 ANOS)', 'precio' => '25', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 6, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '7', 'codigo' => '7', 'descripcion' => 'CERTIFICADO X ANO', 'descripcion_imp' => 'CERTIFICADO X ANO', 'precio' => '11', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 1, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '8', 'codigo' => '8', 'descripcion' => 'CONSTANCIA DE BACHILLER Y O PROFESIONAL', 'descripcion_imp' => 'CONSTANCIA DE BACHILLER Y O PROFESIONAL', 'precio' => '30', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 2, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '9', 'codigo' => '9', 'descripcion' => 'TITULO PROFES. X SUFICIENCIA - A. SOCIALES/BIOMED', 'descripcion_imp' => 'TITULO PROFES. X SUFICIENCIA - A. SOCIALES/BIOMED', 'precio' => '75', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '10', 'codigo' => '10', 'descripcion' => 'CARNET UNIV. DE POSGRADO', 'descripcion_imp' => 'CARNET UNIV. DE POSGRADO', 'precio' => '100', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 4, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '81', 'codigo' => '81', 'descripcion' => 'EXAMEN DE SUFICIENCIA', 'descripcion_imp' => 'EXAMEN DE SUFICIENCIA', 'precio' => '50', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '141', 'codigo' => '141', 'descripcion' => 'ADMISION INSCRIPCION NACIONAL', 'descripcion_imp' => 'ADMISION INSC. NACIONAL', 'precio' => '126', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '142', 'codigo' => '142', 'descripcion' => 'ADMISION INSCRIPCION PARROQUIAL', 'descripcion_imp' => 'ADMISION INSC. PARROQUIAL', 'precio' => '176', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '143', 'codigo' => '143', 'descripcion' => 'ADMISION INSCRIPCION PARTICULAR', 'descripcion_imp' => 'ADMISION INSC. PARTICULAR', 'precio' => '276', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '144', 'codigo' => '144', 'descripcion' => 'ADMISION INSCRIPCION TRASLADOS EXTERNOS', 'descripcion_imp' => 'ADMISION INSC. TRAL. EXT.', 'precio' => '526', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '145', 'codigo' => '145', 'descripcion' => 'ADMISION INSCRIPCION GRADO PROFESIONAL', 'descripcion_imp' => 'ADMISION INSC. GRAD. PROF', 'precio' => '626', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '4', 'codigo' => '4', 'descripcion' => 'ADMISION REINTEGRO', 'descripcion_imp' => 'ADMISION REINTEGRO', 'precio' => '', 'tipo_precio' => 'variable', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '61', 'codigo' => '61', 'descripcion' => 'CEPRUNSA CICLO INTENSIVO', 'descripcion_imp' => 'CEPRUNSA CICLO INTENSIVO', 'precio' => '50', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '62', 'codigo' => '62', 'descripcion' => 'CEPRUNSA NACIONAL', 'descripcion_imp' => 'CEPRUNSA NACIONAL', 'precio' => '150', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '63', 'codigo' => '63', 'descripcion' => 'CEPRUNSA PARROQUIAL', 'descripcion_imp' => 'CEPRUNSA PARROQUIAL', 'precio' => '200', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '64', 'codigo' => '64', 'descripcion' => 'CEPRUNSA PARTICULAR', 'descripcion_imp' => 'CEPRUNSA PARTICULAR', 'precio' => '250', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '65', 'codigo' => '65', 'descripcion' => 'CEPRUNSA PENSION', 'descripcion_imp' => 'CEPRUNSA PENSION', 'precio' => '', 'tipo_precio' => 'variable', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '104010100', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '66', 'codigo' => '66', 'descripcion' => 'CEPRUNSA MATERIAL', 'descripcion_imp' => 'CEPRUNSA MATERIAL', 'precio' => '30', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '104010100', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '116', 'codigo' => '116', 'descripcion' => 'CEPRUNSA EXTRAORDINARIO', 'descripcion_imp' => 'CEPRUNSA EXTRAORDINARIO', 'precio' => '200', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '104010100', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '146', 'codigo' => '146', 'descripcion' => 'CEPRUNSA CICLO PREVIO', 'descripcion_imp' => 'CEPRUNSA CICLO PREVIO', 'precio' => '30', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '147', 'codigo' => '147', 'descripcion' => 'CEPRUNSA BANCO INFORMACION', 'descripcion_imp' => 'CEPRUNSA BANCO INFORMACION', 'precio' => '40', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '197', 'codigo' => '197', 'descripcion' => 'CEPRUNSA DEUDA PENSION', 'descripcion_imp' => 'CEPRUNSA DEUDA PENSION', 'precio' => '', 'tipo_precio' => 'variable', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['id' => '198', 'codigo' => '198', 'descripcion' => 'CEPRUNSA REINTEGRO', 'descripcion_imp' => 'CEPRUNSA REINTEGRO', 'precio' => '', 'tipo_precio' => 'variable', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);

        //Asignación de conceptos por punto de venta
        $puntoVenta1 = PuntosVenta::find(1);
        $puntoVenta1->conceptos()->sync([1, 2, 3]);
        $puntoVenta2 = PuntosVenta::find(2);
        $puntoVenta2->conceptos()->sync([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 81, 141, 142, 143, 144, 145, 61, 62, 63, 64, 65, 66, 116, 146, 147, 197, 198]);
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
