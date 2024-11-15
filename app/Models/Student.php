<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'user_id',
        'student_number',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'contact_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        $name = $this->firstname;

        // Add middle initial if it exists
        if (!empty($this->middlename)) {
            $name .= ' ' . strtoupper(substr($this->middlename, 0, 1)) . '.';
        }

        // Add last name
        $name .= ' ' . $this->lastname;

        // Add suffix if it exists
        if (!empty($this->suffix)) {
            $name .= ', ' . $this->suffix;
        }

        return $name;
    }
}
