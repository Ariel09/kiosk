<?php

namespace App\Filament\Resources\CashierResource\Pages;

use App\Filament\Resources\CashierResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCashier extends ViewRecord
{
    protected static string $resource = CashierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
