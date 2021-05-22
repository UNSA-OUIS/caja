<?php

namespace Database\Factories;

use App\Models\Comprobante;
use App\Models\Dependencia;
use App\Models\TipoComprobante;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DependenciaFactory extends Factory
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
            'tipo_usuario' => 'dependencia',
            'codi_usuario' => function () {
                return Dependencia::inRandomOrder()->first()->codi_depe;
            },
            'tipo_comprobante_id' => function () {
                $this->tipo_comprobante = TipoComprobante::inRandomOrder()->first()->id;
                return $this->tipo_comprobante;
            },
            'serie' => function () {
                if ($this->tipo_comprobante === 1) {
                    return 'B003';
                } elseif ($this->tipo_comprobante === 2) {
                    return 'F003';
                } elseif ($this->tipo_comprobante === 3) {
                    return 'NC03';
                } elseif ($this->tipo_comprobante === 4) {
                    return 'ND03';
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
