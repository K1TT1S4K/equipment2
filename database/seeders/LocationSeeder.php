<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('locations')->insert([
            [
                'name' => 'ห้องA',
                'created_at' => now(),
                'updated_at' => now()
            ],
             [
                'name' => 'ห้องB',
                'created_at' => now(),
                'updated_at' => now()
            ],
             [
                'name' => 'ห้องC',
                'created_at' => now(),
                'updated_at' => now()
            ],
             [
                'name' => 'ห้องD',
                'created_at' => now(),
                'updated_at' => now()
            ],
             [
                'name' => 'ห้องE',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
