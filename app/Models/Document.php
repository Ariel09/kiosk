<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_name',
        'description',
        'price'
    ];

    public function documentRequests()
    {
        return $this->hasMany(DocumentRequest::class, 'document_id');
    }
}