<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('documents')->insert([
            // [
            //     'original_name' => 2020 + 543 . '_equipment_disposal_request_01.pdf',
            //     'stored_name' => '00',
            //     'document_type' => 'ยื่นแทงจำหน่ายครุภัณฑ์',
            //     'date' => now()->format('2020-m-d'),
            //     'created_at' => now()->format('2020-m-d H:i:s'),
            //     'updated_at' => now()->format('2020-m-d H:i:s')
            // ],
        ]);
    }
}
