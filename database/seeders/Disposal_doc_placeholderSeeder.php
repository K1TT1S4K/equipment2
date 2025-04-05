<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Disposal_doc_placeholderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('disposal_doc_placeholder')->insert([
            [
                'writer_name' => 'writer_name',
                'house_number' => '88',
                'village_number' => '12',
                'alley' => 'text',
                'sub_distrinct' => 'text',
                'district' => 'text',
                'province' => 'text',
                'phone_number' => '1234567890',
                'career' => 'text',
                'head_of_department' => 'narakorn ngaongam',
                'chairman_referee' => 'narakorn ngaongam',
                'referee' => 'narakorn ngaongam',
                'referee_and_secretary' => 'narakorn ngaongam',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
