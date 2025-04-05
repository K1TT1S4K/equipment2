<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Equipment_typeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('equipment_types')->insert([
            [
                'name' => 'ชุดครุภัณฑ์ประจำห้องปฏิบัติการวิศวกรรม',
                'amount' => 1,
                'price' => 2620000.00,
                'total_price' => 2620000.00,
                'equipment_unit_id' => 2,
                'description' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ครุภัณฑ์สมองกลฝังตัว',
                'amount' => null,
                'price' => null,
                'total_price' => 1865000.00,
                'equipment_unit_id' => null,
                'description' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ครุภัณฑ์ห้องปฏิบัติการเรียนการสอนสาขาวิชาเทคโนโลยีคอมพิวเตอร์',
                'amount' => 5,
                'price' => 470800.00,
                'total_price' => 2354000.00,
                'equipment_unit_id' => null,
                'description' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
