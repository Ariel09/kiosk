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
                Stat::make('On Hold', DocumentRequest::where('status', 'on_hold')->count())
                ->description('On hold')
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Processing Requests', DocumentRequest::where('status', 'pending')->count())
                ->description('Pending For Release')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Completed Requests', DocumentRequest::where('status', 'completed')->count())
                ->description('Successfully completed requests')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
