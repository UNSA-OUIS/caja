<?php

namespace Database\Seeders;

use App\Models\Admision;
use App\Models\DetallesAdmision;
use Illuminate\Database\Seeder;

class AdmisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admision::create(['proceso_id' => '2', 'monto_total' => '176', 'tipo_colegio' => 'nacional']);
        DetallesAdmision::create(['concepto_id' => '141', 'admision_id' => '1', 'precio_variable' => '126']);
        DetallesAdmision::create(['concepto_id' => '81', 'admision_id' => '1', 'precio_variable' => '50']);
        Admision::create(['proceso_id' => '2', 'monto_total' => '226', 'tipo_colegio' => 'parroquial']);
        DetallesAdmision::create(['concepto_id' => '142', 'admision_id' => '2', 'precio_variable' => '176']);
        DetallesAdmision::create(['concepto_id' => '81', 'admision_id' => '2', 'precio_variable' => '50']);
        Admision::create(['proceso_id' => '2', 'monto_total' => '326', 'tipo_colegio' => 'particular']);
        DetallesAdmision::create(['concepto_id' => '143', 'admision_id' => '3', 'precio_variable' => '276']);
        DetallesAdmision::create(['concepto_id' => '81', 'admision_id' => '3', 'precio_variable' => '50']);
        Admision::create(['proceso_id' => '3', 'monto_total' => '280', 'tipo_colegio' => 'nacional']);
        DetallesAdmision::create(['concepto_id' => '62', 'admision_id' => '4', 'precio_variable' => '150']);
        DetallesAdmision::create(['concepto_id' => '66', 'admision_id' => '4', 'precio_variable' => '30']);
        DetallesAdmision::create(['concepto_id' => '65', 'admision_id' => '4', 'precio_variable' => '100']);
        Admision::create(['proceso_id' => '3', 'monto_total' => '330', 'tipo_colegio' => 'parroquial']);
        DetallesAdmision::create(['concepto_id' => '63', 'admision_id' => '5', 'precio_variable' => '200']);
        DetallesAdmision::create(['concepto_id' => '66', 'admision_id' => '5', 'precio_variable' => '30']);
        DetallesAdmision::create(['concepto_id' => '65', 'admision_id' => '5', 'precio_variable' => '100']);
        Admision::create(['proceso_id' => '3', 'monto_total' => '380', 'tipo_colegio' => 'particular']);
        DetallesAdmision::create(['concepto_id' => '64', 'admision_id' => '6', 'precio_variable' => '250']);
        DetallesAdmision::create(['concepto_id' => '66', 'admision_id' => '6', 'precio_variable' => '30']);
        DetallesAdmision::create(['concepto_id' => '65', 'admision_id' => '6', 'precio_variable' => '100']);
    }
}
