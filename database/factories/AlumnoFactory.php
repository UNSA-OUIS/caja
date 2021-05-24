<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\Comprobante;
use App\Models\TipoComprobante;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlumnoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comprobante::class;
    protected $serie = '';
    protected $tipo_comprobante = '';

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tipo_usuario' => 'alumno',
            'codi_usuario' => function () {
                return Alumno::inRandomOrder()->first()->cui;
            },
            'tipo_comprobante_id' => function () {
                $this->tipo_comprobante = TipoComprobante::inRandomOrder()->first()->id;
                return $this->tipo_comprobante;
            },
            'serie' => function () {
                if ($this->tipo_comprobante === 1) {
                    return 'B001';
                } elseif ($this->tipo_comprobante === 2) {
                    return 'F001';
                } elseif ($this->tipo_comprobante === 3) {
                    return 'NC01';
                } elseif ($this->tipo_comprobante === 4) {
                    return 'ND01';
                }
            },
            'correlativo' => $this->faker->randomNumber(8),
            'total_descuento' => $this->faker->randomNumber(2),
            'total_impuesto' => $this->faker->randomNumber(2),
            'total' => $this->faker->randomNumber(2),
            'estado' => 'noEnviado',
            'cajero_id' => function () {
                return User::inRandomOrder()->first()->id;
            },
            'cancelado' => false,
        ];
    }
}
