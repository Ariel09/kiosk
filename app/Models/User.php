<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasName;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasRoles, HasFactory, Notifiable;

    public function getFilamentName(): string
    {
        return $this->role;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@admin.com');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role',
        'email',
        'password',
        'student_number',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'contact_number'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
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
