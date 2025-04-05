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
            [
                'path' => 'documents/'. 2020+543 .'_equipment_disposal_request' . '_01'. '.pdf',
                'document_type' => 'ยื่นแทงจำหน่ายครุภัณฑ์',
                'date' => now()->format('2020-m-d'),
                'created_at' => now()->format('2020-m-d H:i:s'),
                'updated_at' => now()->format('2020-m-d H:i:s')
            ],
            [
                'path' => 'documents/'. 2020+543 .'_equipment_disposal_form' . '_01'. '.pdf',
                'document_type' => 'แทงจำหน่ายครุภัณฑ์',
                'date' => now()->format('2020-m-d'),
                'created_at' => now()->format('2020-m-d H:i:s'),
                'updated_at' => now()->format('2020-m-d H:i:s')
            ],
            [
                'path' => 'documents/'. 2020+543 .'_equipment_transfer' . '_01'. '.pdf',
                'document_type' => 'โอนครุภัณฑ์',
                'date' => now()->format('2020-m-d'),
                'created_at' => now()->format('2020-m-d H:i:s'),
                'updated_at' => now()->format('2020-m-d H:i:s')
            ],
            [
                'path' => 'documents/'. 2020+543 .'_equipment_transfer' . '_02'. '.pdf',
                'document_type' => 'โอนครุภัณฑ์',
                'date' => now()->format('2020-m-d'),
                'created_at' => now()->format('2020-m-d H:i:s'),
                'updated_at' => now()->format('2020-m-d H:i:s')
            ],
            [
                'path' => 'documents/'. 2020+543 .'_equipment_transfer' . '_03'. '.pdf',
                'document_type' => 'โอนครุภัณฑ์',
                'date' => now()->format('2020-m-d'),
                'created_at' => now()->format('2020-m-d H:i:s'),
                'updated_at' => now()->format('2020-m-d H:i:s')
            ],

            [
                'path' => 'documents/'.(now()->year + 543) .'_equipment_disposal_request' . '_01'. '.pdf',
                'document_type' => 'ยื่นแทงจำหน่ายครุภัณฑ์',
                'date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'path' => 'documents/'.(now()->year + 543) .'_equipment_disposal_form' . '_01'. '.pdf',
                'document_type' => 'แทงจำหน่ายครุภัณฑ์',
                'date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'path' => 'documents/'.(now()->year + 543) .'_equipment_transfer' . '_01'. '.pdf',
                'document_type' => 'โอนครุภัณฑ์',
                'date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'path' => 'documents/'.(now()->year + 543) .'_equipment_transfer' . '_02'. '.pdf',
                'document_type' => 'โอนครุภัณฑ์',
                'date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'path' => 'documents/'.(now()->year + 543) .'_equipment_transfer' . '_03'. '.pdf',
                'document_type' => 'โอนครุภัณฑ์',
                'date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
