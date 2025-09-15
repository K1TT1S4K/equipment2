<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(
            [
                LocationSeeder::class,
                TitleSeeder::class,
                PrefixSeeder::class,
                Disposal_doc_placeholderSeeder::class,
                Equipment_transfer_doc_placeholderSeeder::class,
                Equipment_unitSeeder::class,
                DocumentSeeder::class,
                Equipment_typeSeeder::class,
                UserSeeder::class,
                EquipmentSeeder::class,
                Equipment_documentSeeder::class
            ]
        );
    }
}
