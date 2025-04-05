<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'path',
        'document_type',
        'date'
    ];
    // public function equipment_documents(): HasMany
    // {
    //     return $this->hasMany(Equipment_document::class);
    // }
    // public function decrementType()
    // {
    //     return $this->hasMany(Document::class);
    // }
}
