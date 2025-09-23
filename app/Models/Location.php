<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
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
}
