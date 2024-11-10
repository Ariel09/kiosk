<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Set any default values for the user fields
        $data['role'] = 'student';
        $data['password'] = Hash::make($data['password'] ?? 'defaultpassword');

        // Create the Student record
        $student = static::getModel()::create($data);

        // Assign the 'student' role to the created record
        $student->assignRole('student');

        return $student;
    }
}
