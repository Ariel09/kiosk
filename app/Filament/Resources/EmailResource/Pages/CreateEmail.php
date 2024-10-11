<?php

namespace App\Filament\Resources\EmailResource\Pages;

use App\Filament\Resources\EmailResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmail extends CreateRecord
{
    protected static string $resource = EmailResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Send email
        EmailResource::sendEmail($data);

        return $data;
    }

    protected function afterCreate(): void
    {
        // Redirect after sending email
        $this->redirect('/path-to-your-html-form');
    }
}