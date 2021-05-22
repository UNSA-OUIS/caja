<?php

namespace Database\Factories;

use App\Models\Docente;
use App\Models\TipoComprobante;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocenteFactory extends Factory
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
            'tipo_usuario' => 'docente',
            'codi_usuario' => function () {
                return Docente::inRandomOrder()->first()->codper;
            },
            'tipo_comprobante_id' => function () {
                $this->tipo_comprobante = TipoComprobante::inRandomOrder()->first()->id;
                return $this->tipo_comprobante;
            },
            'serie' => function () {
                if ($this->tipo_comprobante === 1) {
                    return 'B002';
                } elseif ($this->tipo_comprobante === 2) {
                    return 'F002';
                } elseif ($this->tipo_comprobante === 3) {
                    return 'NC02';
                } elseif ($this->tipo_comprobante === 4) {
                    return 'ND02';
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
