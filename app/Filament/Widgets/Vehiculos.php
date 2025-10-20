<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Vehiculos extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Cantidad de Vehículos', 0)
                ->description('Total de vehículos registrados en el sistema'),
        ];
    }
}
