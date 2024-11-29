<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->belongsToMany(DocumentRequest::class, 'document_request_document', 'document_id', 'document_request_id');
    }

    public function documentRequestDocuments(): HasMany
    {
        return $this->hasMany(DocumentRequestDocument::class);
    }
}
