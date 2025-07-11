<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    //
    use HasFactory;
    use SoftDeletes;
    protected $table = 'documents';
    protected $fillable = [
        'original_name',
        'stored_name',
        'document_type',
        'date'
    ];
    protected $dates = ['deleted_at'];
    // public function equipment_documents(): HasMany
    // {
    //     return $this->hasMany(Equipment_document::class);
    // }
    // public function decrementType()
    // {
    //     return $this->hasMany(Document::class);
    // }
}
