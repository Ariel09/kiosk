<?php

namespace App\Filament\Resources\ReleaseDocumentResource\Pages;

use App\Filament\Resources\ReleaseDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReleaseDocument extends EditRecord
{
    protected static string $resource = ReleaseDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
