<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registrar extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'document_requests';

    // Define a default scope for the "pending" or "released" statuses
    protected static function booted()
    {
        static::addGlobalScope('paidOrPendingOrReleased', function ($query) {
            $query->whereIn('status', ['paid', 'pending', 'released']);
        });
    }

    // Specify fillable fields (optional but recommended for mass assignment)
    protected $fillable = [
        'user_id',
        'program',
        'year_level',
        'status',
        'queue_number',
        'amount',
        'payment_date',
        'released_date',
        'remarks'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_request_document', 'document_request_id', 'document_id');
    }
}
