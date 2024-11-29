<?php

namespace App\Models;

use Database\Seeders\DocumentSeeder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DocumentRequestDocument extends Pivot
{
    protected $table = 'document_request_document';

    protected $fillable = [
        'document_request_id',
        'document_id',
        'quantity',
        'price',
    ];

    public function documentRequest(): BelongsTo
    {
        return $this->belongsTo(DocumentRequest::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
