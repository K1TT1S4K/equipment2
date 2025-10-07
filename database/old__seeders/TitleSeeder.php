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
                'id' => 1,
                'name' => 'สาขาเทคโนโลยีคอมพิวเตอร์ 2568',
                'is_locked' => 0,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 12:54:50',
                'updated_at' => '2025-09-25 13:00:24',
                'deleted_at' => null,
            ],
        ]);
    }
}
