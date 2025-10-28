<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Usuarios extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Usuarios, Total', 2)
                ->description('Cantidad de usuarios registrados'),
        ];
    }
}
