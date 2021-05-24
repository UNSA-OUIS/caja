<?php

namespace Database\Seeders;

use App\Models\Particular;
use Illuminate\Database\Seeder;

class ParticularSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Particular::factory()->count(50)->create();
    }
}
