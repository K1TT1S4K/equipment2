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
                'name' => 'ห้องเก็บของ 1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'อท.505',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ตู้ อท.505',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'อท.507',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'อท.501',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ชั้น 5',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'อท.601',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'อท.503',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'สนง.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'อท.509',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
