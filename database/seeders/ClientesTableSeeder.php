<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Cliente;
use App\Models\Endereco;

class ClientesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 300; $i++) {
            $endereco = Endereco::inRandomOrder()->first();

            $cliente = new Cliente([
                'cpf' => $faker->unique()->numerify('###########'),
                'nome' => $faker->name(),
                'data_nascimento' => $faker->date(),
                'sexo' => $faker->randomElement(['M', 'F']),
            ]);

            $cliente->endereco()->associate($endereco);

            $cliente->save();
        }
    }
}
