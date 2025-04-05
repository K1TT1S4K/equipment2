<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Equipment extends Model
{
    //
    protected $fillable = [
        'number',
        'name',
        'amount',
        'price',
        'total_price',
        'status',
        'group',
        'equipment_type_id',
        'equipment_unit_id',
        'location',
        'title',
        'description',
        'user_id',
    ];

    // ความสัมพันธ์กับ Equipment_type
    public function equipmentType()
    {
        return $this->belongsTo(Equipment_type::class, 'equipment_type_id');
    }

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
}
