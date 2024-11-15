<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Start a transaction to ensure both records are updated atomically
        return DB::transaction(function () use ($data) {
            // Update the Student record
            $student = $this->record;
            $student->update([
                'student_number' => $data['student_number'],
                'firstname' => $data['firstname'],
                'middlename' => $data['middlename'],
                'lastname' => $data['lastname'],
                'suffix' => $data['suffix'],
                'contact_number' => $data['contact_number'],
            ]);

            // Update the related User record
            $user = $student->user;  // Assuming 'user' relationship exists
            $user->update([
                'email' => $data['email'] ?? $user->email,  // Only update email if provided
                'password' => isset($data['password']) ? Hash::make($data['password']) : $user->password,  // Hash password if provided
            ]);

            // Return the updated Student model
            return $student;
        });
    }
}
