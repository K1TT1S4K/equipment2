<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment_unit extends Model
{
    //
    protected $fillable = [
        'name',
        'is_locked',

    ];

    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    // public function equipment_types(): HasMany
    // {
    //     return $this->hasMany(Equipment_type::class);
    // }
}
