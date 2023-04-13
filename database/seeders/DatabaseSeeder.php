<?php

namespace Database\Seeders;
// use App\Database\Seeds\ClientesTableSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(EnderecosTableSeeder::class);
        $this->call(ClientesTableSeeder::class);
    }
}
