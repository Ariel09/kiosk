<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Student extends Model
{
    use HasFactory, HasRoles;

    protected $guard_name = 'web';

    // Specify the table associated with the model
    protected $table = 'users';

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'contact_number',
        'email',
        'password',
        'role',
        'student_number', // Add student_number here to allow mass assignment
    ];

    // Add a scope to filter by role if necessary
    protected static function booted()
    {
        static::addGlobalScope('student', function ($query) {
            $query->where('role', 'student');
        });
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
