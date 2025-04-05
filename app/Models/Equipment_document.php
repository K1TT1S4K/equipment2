<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment_document extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'equipment_id',
        'document_id',
        'description',
    ];

    public function equipments() : HasMany {
        return $this->hasMany(Equipment::class);
    }
    public function documents() : HasMany {
        return $this->hasMany(Document::class);
    }
}
