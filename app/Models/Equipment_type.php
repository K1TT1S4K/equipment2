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
    ];
    // public function equipments(): HasMany
    // {
    //     return $this->hasMany(Equipment::class);
    // }
}
