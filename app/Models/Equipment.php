<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'number',
        'name',
        'amount',
        'price',
        'total_price',
        // 'status_found',
        // 'status_not_found',
        // 'status_broken',
        // 'status_disposal',
        'status_transfer',
        'equipment_unit_id',
        'location_id',
        'title_id',
        'description',
        'user_id',
        'stored_image_name',
        'original_image_name',
        'original_id',
        'created_at',
        'updated_at'
    ];

    // public function documents(): HasMany
    // {
    //     // foreign key ใน table equipment_documents คือ equipment_id
    //     return $this->hasMany(Equipment_document::class, 'equipment_id');
    // }

    // ความสัมพันธ์กับ Equipment_unit
    public function equipmentUnit()
    {
        return $this->belongsTo(Equipment_unit::class, 'equipment_unit_id');
    }

    // ความสัมพันธ์กับ Location
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    // ความสัมพันธ์กับ User (ผู้รับผิดชอบ)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function title()
    {
        return $this->belongsTo(Title::class, 'title_id');
    }

    // public function getStatusNotFound()
    // {
    //     return $this->hasMany(Equipment_document::class, 'equipment_id') // ชี้ไปที่ equipment_id
    //         ->whereHas('document', function ($q) {
    //             $q->where('document_type', 'ไม่พบ'); // filter จาก document ที่ join
    //         });
    // }

    // public function getStatusBroken()
    // {
    //     return $this->hasMany(Equipment_document::class, 'equipment_id') // ชี้ไปที่ equipment_id
    //         ->whereHas('document', function ($q) {
    //             $q->where('document_type', 'ชำรุด'); // filter จาก document ที่ join
    //         });
    // }

    // public function getStatusTransfer()
    // {
    //     return $this->hasMany(Equipment_document::class, 'equipment_id') // ชี้ไปที่ equipment_id
    //         ->whereHas('document', function ($q) {
    //             $q->where('document_type', 'โอนครุภัณฑ์'); // filter จาก document ที่ join
    //         });
    // }

    // public function getStatusDisposal()
    // {
    //     return $this->hasMany(Equipment_document::class, 'equipment_id') // ชี้ไปที่ equipment_id
    //         ->whereHas('document', function ($q) {
    //             $q->where('document_type', 'แทงจำหน่ายครุภัณฑ์'); // filter จาก document ที่ join
    //         });
    // }
}
