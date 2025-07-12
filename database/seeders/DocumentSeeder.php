<?php

namespace Database\Seeders;

use App\Models\Document;
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
        // DB::table('documents')->insert([
        // ]);

        // สร้าง 20 รายการยังไม่ลบ
        Document::factory()->count(20)->create();

        // สร้าง 5 รายการที่ถูกลบแล้ว
        Document::factory()->count(5)->deleted()->create();
    }
}
