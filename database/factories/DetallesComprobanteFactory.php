<?php

namespace Database\Factories;

use App\Models\Comprobante;
use App\Models\Concepto;
use App\Models\DetallesComprobante;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetallesComprobanteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DetallesComprobante::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cantidad' => $this->faker->randomDigit(1, 10),
            'valor_unitario' => $this->faker->randomNumber(2),
            'gravado' => $this->faker->randomNumber(2),
            'inafecto' => $this->faker->randomNumber(2),
            'impuesto' => $this->faker->randomNumber(2),
            'descuento' => $this->faker->randomNumber(2),
            'estado' => true,
            'tipo_descuento' => $this->faker->randomElement(['soles', 'porcentaje']),
            'subtotal' => $this->faker->randomNumber(2),
            'concepto_id' => function () {
                return Concepto::inRandomOrder()->first()->id;
            },
            'comprobante_id' => function () {
                return Comprobante::inRandomOrder()->first()->id;
            },
        ];
    }
}
