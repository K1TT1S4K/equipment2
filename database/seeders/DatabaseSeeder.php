<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(
            [
                TitleSeeder::class,
                PrefixSeeder::class,
                Equipment_unitSeeder::class,
                UserSeeder::class,
                EquipmentSeeder::class,
            ]
        );
    }
}
