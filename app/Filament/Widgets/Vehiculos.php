<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Vehiculos extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    protected function getStats(): array
    {
        $totalVehiculos = Vehicle::count();

        return [
            Stat::make('Total de Vehículos', $totalVehiculos)
                ->description('Vehículos registrados en el sistema')
                ->descriptionIcon('heroicon-o-truck')
                ->color('primary')
                ->icon('heroicon-o-truck')
                ->url(\App\Filament\Resources\Vehicles\VehicleResource::getUrl('index')),
        ];
    }
}
