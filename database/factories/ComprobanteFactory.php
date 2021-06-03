<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\Comprobante;
use App\Models\Dependencia;
use App\Models\Docente;
use App\Models\Empresa;
use App\Models\Particular;
use App\Models\TipoComprobante;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComprobanteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comprobante::class;

    protected $serie = '';
    protected $tipo_comprobante = '';
    protected $tipo_usuario = '';
    protected $codi_usuario = '';

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tipo_usuario' => function () {
                $this->tipo_usuario = $this->faker->randomElement(['alumno', 'docente', 'dependencia', 'particular', 'empresa']);
                return $this->tipo_usuario;
            },
            'codi_usuario' => function () {
                if ($this->tipo_usuario == 'alumno') {
                    return Alumno::inRandomOrder()->first()->cui;
                } elseif ($this->tipo_usuario == 'docente') {
                    return Docente::inRandomOrder()->first()->codper;
                } elseif ($this->tipo_usuario == 'dependencia') {
                    return Dependencia::inRandomOrder()->first()->codi_depe;
                } elseif ($this->tipo_usuario == 'particular') {
                    return Particular::inRandomOrder()->first()->dni;
                } elseif ($this->tipo_usuario == 'empresa') {
                    return Empresa::inRandomOrder()->first()->ruc;
                }
            },
            'tipo_comprobante_id' => function () {
                $this->tipo_comprobante = TipoComprobante::inRandomOrder()->first()->id;
                return $this->tipo_comprobante;
            },
            'serie' => function () {
                if ($this->tipo_comprobante == 1 && $this->tipo_usuario != 'empresa') {
                    return 'B001';
                } elseif ($this->tipo_comprobante == 2 && $this->tipo_usuario == 'empresa') {
                    return 'F001';
                } elseif ($this->tipo_comprobante == 3) {
                    return 'ND01';
                } elseif ($this->tipo_comprobante == 4) {
                    return 'NC01';
                } else {
                    return 'ND01';
                }
            },
            'correlativo' => '00000' . $this->faker->randomNumber(3),
            'total_descuento' => $this->faker->randomNumber(2),
            'total_impuesto' => $this->faker->randomNumber(2),
            'total_inafecta' => $this->faker->randomNumber(2),
            'total_gravada' => $this->faker->randomNumber(2),
            'total' => $this->faker->randomNumber(2),
            'estado' => 'noEnviado',
            'cajero_id' => function () {
                return User::inRandomOrder()->first()->id;
            },
            'cancelado' => false,
        ];
    }
}
