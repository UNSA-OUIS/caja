<?php

namespace Database\Factories;


use App\Models\Comprobante;
use App\Models\TipoComprobante;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class ComprobanteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comprobante::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tipo_usuario' => $this->faker->randomElement(['alumno', 'docente', 'dependencia', 'particular', 'empresa']),
            'tipo_comprobante_id' => function (array $post) {
                return TipoComprobante::inRandomOrder()->first()->id;
            },
            'serie' => $this->faker->randomElement(['B001', 'F001', 'B002', 'F002']),
            'correlativo' => $this->faker->randomNumber(8),
            'total_descuento' => $this->faker->randomNumber(2),
            'total_impuesto' => $this->faker->randomNumber(2),
            'total' => $this->faker->randomNumber(2),
            'estado' => 'noEnviado',
            'cajero_id' => function (array $post) {
                return User::inRandomOrder()->first()->id;
            },
            'cancelado' => false,
        ];
    }
}
