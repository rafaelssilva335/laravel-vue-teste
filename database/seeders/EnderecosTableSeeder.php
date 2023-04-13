<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class EnderecosTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 300; $i++) {
            DB::table('enderecos')->insert([
                'endereco' => $faker->address(),
                'estado' => $faker->state(),
                'cidade' => $faker->city(),
            ]);
        }
    }
}
