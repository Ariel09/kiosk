<?php

namespace App\Filament\Resources\DocumentRequestResource\Widgets;

use App\Models\DocumentRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DocumentRequestOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Requests', DocumentRequest::count())
                ->description('Total document requests')
                ->icon('heroicon-o-document')
                ->color('primary'),

            Stat::make('Pending Requests', DocumentRequest::where('status', 'pending')->count())
                ->description('Requests pending approval')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Completed Requests', DocumentRequest::where('status', 'completed')->count())
                ->description('Successfully completed requests')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
