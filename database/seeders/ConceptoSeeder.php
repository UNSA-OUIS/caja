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

        Concepto::create(['codigo' => '1', 'descripcion' => 'MATRICULA DE MAESTRIA O DOCTORADO', 'descripcion_imp' => 'MATRICULA MAEST. O DOCTO.', 'precio' => '100', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 1, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '2', 'descripcion' => 'CONSTANCIA DE MAESTRIA', 'descripcion_imp' => 'CONSTANCIA DE MAESTRIA', 'precio' => '20', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '10', 'tipo_concepto_id' => '1', 'clasificador_id' => 2, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '3', 'descripcion' => 'CAMBIO DE APELLIDOS Y NOMBRE', 'descripcion_imp' => 'CAMBIO APELLIDOS/NOMBRES', 'precio' => '', 'tipo_precio' => 'variable', 'tipo_afectacion' => '10', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '4', 'descripcion' => 'CONSTANCIA DE EGRESADO - MAESTRIA', 'descripcion_imp' => 'CONST.EGRES.MAES/DOC', 'precio' => '80', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 4, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '5', 'descripcion' => 'CERTIFICADO X SEMESTRE MAESTRIA/2DAS/RESID.MEDICO', 'descripcion_imp' => 'CERT. P/SEMESTRE MAES/2DA', 'precio' => '200', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 5, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '6', 'descripcion' => 'CERTIFICADO X ANO (MAS DE 10 ANOS)', 'descripcion_imp' => 'CERT. P/ANO(+ DE 10 ANOS)', 'precio' => '25', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 6, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '7', 'descripcion' => 'CERTIFICADO X ANO', 'descripcion_imp' => 'CERTIFICADO X ANO', 'precio' => '11', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 1, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '8', 'descripcion' => 'CONSTANCIA DE BACHILLER Y O PROFESIONAL', 'descripcion_imp' => 'CONSTANCIA DE BACHILLER Y O PROFESIONAL', 'precio' => '30', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 2, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '9', 'descripcion' => 'TITULO PROFES. X SUFICIENCIA - A. SOCIALES/BIOMED', 'descripcion_imp' => 'TITULO PROFES. X SUFICIENCIA - A. SOCIALES/BIOMED', 'precio' => '75', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '10', 'descripcion' => 'CARNET UNIV. DE POSGRADO', 'descripcion_imp' => 'CARNET UNIV. DE POSGRADO', 'precio' => '100', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 4, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '306151900', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '11', 'descripcion' => 'EXAMEN DE SUFICIENCIA', 'descripcion_imp' => 'EXAMEN DE SUFICIENCIA', 'precio' => '50.00', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '12', 'descripcion' => 'ADMISION INSCRIPCION NACIONAL', 'descripcion_imp' => 'ADMISION INSC. NACIONAL', 'precio' => '126.00', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '13', 'descripcion' => 'ADMISION INSCRIPCION PARROQUIAL', 'descripcion_imp' => 'ADMISION INSC. PARROQUIAL', 'precio' => '176.00', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '14', 'descripcion' => 'ADMISION INSCRIPCION PARTICULAR', 'descripcion_imp' => 'ADMISION INSC. PARTICULAR', 'precio' => '276.00', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '15', 'descripcion' => 'ADMISION INSCRIPCION TRASLADOS EXTERNOS', 'descripcion_imp' => 'ADMISION INSC. TRAL. EXT.', 'precio' => '526.00', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '16', 'descripcion' => 'ADMISION INSCRIPCION GRADO PROFESIONAL', 'descripcion_imp' => 'ADMISION INSC. GRAD. PROF', 'precio' => '626.00', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '17', 'descripcion' => 'ADMISION REINTEGRO', 'descripcion_imp' => 'ADMISION REINTEGRO', 'precio' => '', 'tipo_precio' => 'variable', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '18', 'descripcion' => 'CEPRUNSA CICLO INTENSIVO', 'descripcion_imp' => 'CEPRUNSA CICLO INTENSIVO', 'precio' => '50.00', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '19', 'descripcion' => 'CEPRUNSA NACIONAL', 'descripcion_imp' => 'CEPRUNSA NACIONAL', 'precio' => '150.00', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '20', 'descripcion' => 'CEPRUNSA PARROQUIAL', 'descripcion_imp' => 'CEPRUNSA PARROQUIAL', 'precio' => '200.00', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '21', 'descripcion' => 'CEPRUNSA PARTICULAR', 'descripcion_imp' => 'CEPRUNSA PARTICULAR', 'precio' => '250.00', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '22', 'descripcion' => 'CEPRUNSA PENSION', 'descripcion_imp' => 'CEPRUNSA PENSION', 'precio' => '', 'tipo_precio' => 'variable', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '104010100', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '23', 'descripcion' => 'CEPRUNSA MATERIAL', 'descripcion_imp' => 'CEPRUNSA MATERIAL', 'precio' => '30.00', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '104010100', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '24', 'descripcion' => 'CEPRUNSA EXTRAORDINARIO', 'descripcion_imp' => 'CEPRUNSA EXTRAORDINARIO', 'precio' => '200.00', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '104010100', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '25', 'descripcion' => 'CEPRUNSA CICLO PREVIO', 'descripcion_imp' => 'CEPRUNSA CICLO PREVIO', 'precio' => '30.00', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '26', 'descripcion' => 'CEPRUNSA BANCO INFORMACION', 'descripcion_imp' => 'CEPRUNSA BANCO INFORMACION', 'precio' => '40.00', 'tipo_precio' => 'fijo', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '27', 'descripcion' => 'CEPRUNSA DEUDA PENSION', 'descripcion_imp' => 'CEPRUNSA DEUDA PENSION', 'precio' => '', 'tipo_precio' => 'variable', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);
        Concepto::create(['codigo' => '28', 'descripcion' => 'CEPRUNSA REINTEGRO', 'descripcion_imp' => 'CEPRUNSA REINTEGRO', 'precio' => '', 'tipo_precio' => 'variable', 'tipo_afectacion' => '30', 'tipo_concepto_id' => '1', 'clasificador_id' => 3, 'unidad_medida_id' => '59', 'semestre' => '1', 'codi_depe' => '000000000', 'estado' => 'true', 'detraccion' => 'false']);

        //Asignación de conceptos por punto de venta
        $puntoVenta1 = PuntosVenta::find(1);
        $puntoVenta1->conceptos()->sync([1, 2, 3]);
        $puntoVenta2 = PuntosVenta::find(2);
        $puntoVenta2->conceptos()->sync([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28]);
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
