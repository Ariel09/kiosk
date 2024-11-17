<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentRequestFactory> */

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'program',
        'year_level',
        'status',
        'queue_number',
        'amount',
        'payment_date',
        'remarks'
    ];

    /**
     * Get the user that owns the document request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_request_document');
    }
}
