<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Equipment_documentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('equipment_documents')->insert([
            [
                'equipment_id' => 24,
                'document_id' => 3,
                'description' => 'โอน'
            ],
            [
                'equipment_id' => 25,
                'document_id' => 2,
                'description' => 'แทง'
            ],
        ]);
    }
}
