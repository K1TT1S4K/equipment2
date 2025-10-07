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
                'id' => 1,
                'name' => 'ห้องเก็บของ 1',
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
                'name' => 'อท.505',
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
                'name' => 'ตู้ อท.505',
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
                'name' => 'อท.507',
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
                'name' => 'อท.501',
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
                'name' => 'ชั้น 5',
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
                'name' => 'อท.601',
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
                'name' => 'อท 507',
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
                'name' => 'ห้องแม่บ้าน',
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
                'name' => 'อท 509',
                'is_locked' => 0,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 12:54:50',
                'updated_at' => '2025-09-25 12:54:50',
                'deleted_at' => null,
            ],
            [
                'id' => 11,
                'name' => 'อ วาทการ',
                'is_locked' => 0,
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => '2025-09-25 12:54:50',
                'updated_at' => '2025-09-25 12:54:50',
                'deleted_at' => null,
            ],
            [
                'id' => 12,
                'name' => 'อท 503',
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
