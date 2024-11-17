<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashier extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'document_requests';

    // Specify fillable fields (optional but recommended for mass assignment)
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

    // Define a default scope for the "on_hold" status
    protected static function booted()
    {
        static::addGlobalScope('onHold', function ($query) {
            $query->where('status', 'on_hold');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_request_document', 'document_request_id', 'document_id');
    }
}
