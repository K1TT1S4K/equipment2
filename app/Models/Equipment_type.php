<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment_type extends Model
{
    //
    // protected $table = 'equipment_types';
    protected $fillable = [
        'name',
        'equipment_unit_id',
        'amount',
        'price'
    ];
    // public function equipments(): HasMany
    // {
    //     return $this->hasMany(Equipment::class);
    // }

    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    public function equipment_unit()
    {
        return $this->belongsTo(Equipment_unit::class, 'equipment_unit_id');
    }    
}
