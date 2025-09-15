<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Equipment_unitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('equipment_units')->insert([
            [
                'name' => 'จ.1/1/50 จอ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'จ.1/12/51',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'เครื่อง',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ตู้',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ตัว',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ชุด',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'บอร์ด',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
