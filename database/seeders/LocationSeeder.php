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
        'name' => 'ห้องA',
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
        'name' => 'ห้องB',
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
        'name' => 'ห้องC',
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
        'name' => 'ห้องD',
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
        'name' => 'ห้องE',
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
        'name' => 'ห้องA',
        'is_locked' => 1,
        'created_by' => null,
        'updated_by' => null,
        'deleted_by' => null,
        'created_at' => '2025-09-25 13:00:24',
        'updated_at' => '2025-09-25 13:00:24',
        'deleted_at' => null,
    ],
    [
        'id' => 7,
        'name' => 'ห้องC',
        'is_locked' => 1,
        'created_by' => null,
        'updated_by' => null,
        'deleted_by' => null,
        'created_at' => '2025-09-25 13:00:24',
        'updated_at' => '2025-09-25 13:00:24',
        'deleted_at' => null,
    ],
    [
        'id' => 8,
        'name' => 'ห้องA',
        'is_locked' => 1,
        'created_by' => null,
        'updated_by' => null,
        'deleted_by' => null,
        'created_at' => '2025-09-25 13:00:24',
        'updated_at' => '2025-09-25 13:00:24',
        'deleted_at' => null,
    ],
    [
        'id' => 9,
        'name' => 'ห้องA',
        'is_locked' => 1,
        'created_by' => null,
        'updated_by' => null,
        'deleted_by' => null,
        'created_at' => '2025-09-25 13:01:40',
        'updated_at' => '2025-09-25 13:01:40',
        'deleted_at' => null,
    ],
    [
        'id' => 10,
        'name' => 'ห้องC',
        'is_locked' => 1,
        'created_by' => null,
        'updated_by' => null,
        'deleted_by' => null,
        'created_at' => '2025-09-25 13:01:40',
        'updated_at' => '2025-09-25 13:01:40',
        'deleted_at' => null,
    ],
    [
        'id' => 11,
        'name' => 'ห้องA',
        'is_locked' => 1,
        'created_by' => null,
        'updated_by' => null,
        'deleted_by' => null,
        'created_at' => '2025-09-25 13:01:40',
        'updated_at' => '2025-09-25 13:01:40',
        'deleted_at' => null,
    ],
    [
        'id' => 12,
        'name' => 'ห้องC',
        'is_locked' => 1,
        'created_by' => null,
        'updated_by' => null,
        'deleted_by' => null,
        'created_at' => '2025-09-25 13:26:39',
        'updated_at' => '2025-09-25 13:26:39',
        'deleted_at' => null,
    ],
    [
        'id' => 13,
        'name' => 'ห้องA',
        'is_locked' => 1,
        'created_by' => null,
        'updated_by' => null,
        'deleted_by' => null,
        'created_at' => '2025-09-25 13:26:39',
        'updated_at' => '2025-09-25 13:26:39',
        'deleted_at' => null,
    ],
        ]);
    }
}
