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
                'name' => 'สาขาเทคโนโลยีคอมพิวเตอร์ 65',
                'is_locked' => 1,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 12:54:50',
                'updated_at' => '2025-09-25 13:00:24',
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'name' => 'สาขาเทคโนโลยีคอมพิวเตอร์ 66',
                'is_locked' => 1,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 13:00:24',
                'updated_at' => '2025-09-25 13:01:40',
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'name' => 'สาขาเทคโนโลยีคอมพิวเตอร์ 67',
                'is_locked' => 1,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 13:01:40',
                'updated_at' => '2025-09-25 13:26:39',
                'deleted_at' => null,
            ],
            [
                'id' => 4,
                'name' => 'สาขาเทคโนโลยีคอมพิวเตอร์ 68',
                'is_locked' => 0,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 13:26:39',
                'updated_at' => '2025-09-25 13:26:59',
                'deleted_at' => null,
            ],
        ]);
    }
}
