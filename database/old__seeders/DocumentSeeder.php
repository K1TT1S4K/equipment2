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
            //     'original_name' => 'PDF-1.pdf',
            //     'stored_name' => 'PDF-1.pdf',
            //     'document_type' => 'สรุปรายงานผลการตรวจสอบครุภัณฑ์ประจำปี',
            //     'date' => '2025-09-20',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'original_name' => 'PDF-2.pdf',
            //     'stored_name' => 'PDF-2.pdf',
            //     'document_type' => 'รายการจำหน่ายก่อนประเมินพัสดุครุภัณฑ์ชำรุด',
            //     'date' => '2025-09-20',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'original_name' => 'PDF-3.pdf',
            //     'stored_name' => 'PDF-3.pdf',
            //     'document_type' => 'โอนครุภัณฑ์',
            //     'date' => '2025-09-20',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ]
        ]);
    }
}
