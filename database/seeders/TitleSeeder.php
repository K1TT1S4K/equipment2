<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('titles')->insert([
            [
                'name' => 'สาขาเทคโนโลยีคอมพิวเตอร์',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'สาขาเทคโนโลยีคอมพิวเตอร์ 59',
                'create_at' => now(),
                'updated_at' => now()
            ]
            ]);
    }
}
