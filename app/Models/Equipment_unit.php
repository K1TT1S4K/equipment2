<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment_unit extends Model
{
    //
    protected $fillable = [
        'name',
    ];
    // public function equipments(): HasMany
    // {
    //     return $this->hasMany(Equipment::class);
    // }

    // public function Equipment_unit() : HasMany {
    //     return $this->hasMany(Equipment_unit::class);
    // }
}
