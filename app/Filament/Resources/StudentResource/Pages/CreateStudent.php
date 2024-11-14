<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\Student;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        Log::info('Data', ['data' => $data]);
        return DB::transaction(function () use ($data) {
            // Create the user record with essential fields
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?? 'defaultpassword'),
                'name' => 'student', // Placeholder for name, if needed
            ]);

            // Assign the 'student' role to the user
            $user->assignRole('student');

            // Create the student record, associating with the user
            $student = Student::create([
                'user_id' => $user->id,
                'student_number' => $data['student_number'],
                'firstname' => $data['firstname'],
                'middlename' => $data['middlename'] ?? null,
                'lastname' => $data['lastname'],
                'suffix' => $data['suffix'] ?? null,
                'contact_number' => $data['contact_number'],
            ]);

            return $student;
        });
    }
}
