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
                'id' => 1,
                'name' => 'จอ',
                'is_locked' => 0,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 12:54:50',
                'updated_at' => '2025-09-25 12:54:50',
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'name' => 'เครื่อง',
                'is_locked' => 0,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 12:54:50',
                'updated_at' => '2025-09-25 12:54:50',
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'name' => 'ตู้',
                'is_locked' => 0,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 12:54:50',
                'updated_at' => '2025-09-25 12:54:50',
                'deleted_at' => null,
            ],
            [
                'id' => 4,
                'name' => 'ตัว',
                'is_locked' => 0,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 12:54:50',
                'updated_at' => '2025-09-25 12:54:50',
                'deleted_at' => null,
            ],
            [
                'id' => 5,
                'name' => 'ชุด',
                'is_locked' => 0,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 12:54:50',
                'updated_at' => '2025-09-25 12:54:50',
                'deleted_at' => null,
            ],
            [
                'id' => 6,
                'name' => 'บอร์ด',
                'is_locked' => 0,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 12:54:50',
                'updated_at' => '2025-09-25 12:54:50',
                'deleted_at' => null,
            ],
            [
                'id' => 7,
                'name' => 'จ.1/7/53',
                'is_locked' => 0,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 12:54:50',
                'updated_at' => '2025-09-25 12:54:50',
                'deleted_at' => null,
            ],
            [
                'id' => 8,
                'name' => 'จ.1/8/53',
                'is_locked' => 0,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 12:54:50',
                'updated_at' => '2025-09-25 12:54:50',
                'deleted_at' => null,
            ],
            [
                'id' => 9,
                'name' => 'จ.2/12/55',
                'is_locked' => 0,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 12:54:50',
                'updated_at' => '2025-09-25 12:54:50',
                'deleted_at' => null,
            ],
            [
                'id' => 10,
                'name' => '1/9/56 เครื่อง',
                'is_locked' => 0,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 12:54:50',
                'updated_at' => '2025-09-25 12:54:50',
                'deleted_at' => null,
            ],
        ]);
    }
}
