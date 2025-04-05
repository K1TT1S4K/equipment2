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
        Schema::create('equipment_transfer_doc_placeholder', function (Blueprint $table) {
            $table->id();
            $table->string('government_agency');
            $table->string('at');
            $table->date('date');
            $table->string('title');
            $table->string('dear_position');
            $table->text('paragraph_before_table');
            $table->text('paragraph_after_table');
            $table->string('sender_name');
            $table->string('sender_position');
            $table->string('receiver_name');
            $table->string('receiver_position');
            $table->string('first_witness_name');
            $table->string('first_witness_position');
            $table->string('second_witness_name');
            $table->string('second_witness_position');
            $table->string('third_witness_name');
            $table->string('third_witness_position');
            $table->string('approver_name');
            $table->string('approver_position');
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
        Schema::dropIfExists('equipment_transfer_doc_placeholder');
    }
};
