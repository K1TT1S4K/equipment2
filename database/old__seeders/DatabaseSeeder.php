<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(
            [
                DocumentSeeder::class,
                LocationSeeder::class,
                TitleSeeder::class,
                PrefixSeeder::class,
                Equipment_unitSeeder::class,
                UserSeeder::class,
                EquipmentSeeder::class,
            ]
        );
    }
}
