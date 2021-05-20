<?php

namespace Database\Factories;


use App\Models\Comprobante;
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
        $factory = Factory::create();
        $factory->define(Comprobante::class, function(Faker $faker){
            return [
                //
            ];
        });

    }
}
