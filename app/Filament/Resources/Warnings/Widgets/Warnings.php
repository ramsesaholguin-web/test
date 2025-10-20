<?php

namespace App\Filament\Resources\Warnings\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Warnings extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Warnings')
                ->description('All Warnings')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->icon('heroicon-o-exclamation-triangle')
                ->value(\App\Models\Warning::count()),
            Stat::make('Active Warnings')
                ->description('Warnings Active')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color('warning')
                ->icon('heroicon-o-exclamation-circle')
                ->value(\App\Models\Warning::where('status', 'active')->count()),
            Stat::make('Resolved Warnings')
                ->description('Warnings Resolved')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->value(\App\Models\Warning::where('status', 'resolved')->count()),
        ];
    }
}
