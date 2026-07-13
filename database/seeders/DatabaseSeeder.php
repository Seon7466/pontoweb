<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EmpresaSeeder::class,
            DepartamentoSeeder::class,
            CargoSeeder::class,
            FuncionarioSeeder::class,
        ]);
    }
}
