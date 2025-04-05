<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('disposal_doc_placeholder', function (Blueprint $table) {
            $table->id();
            $table->string('writer_name');
            $table->string('house_number');
            $table->string('village_number');
            $table->string('alley');
            $table->string('sub_distrinct');
            $table->string('district');
            $table->string('province');
            $table->string('phone_number');
            $table->string('career');
            $table->string('head_of_department');
            $table->string('chairman_referee');
            $table->string('referee');
            $table->string('referee_and_secretary');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // ผู้สร้าง
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // ผู้แก้ไขล่าสุด
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null'); // ผู้ลบ
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposal_doc_placeholder');
    }
};
