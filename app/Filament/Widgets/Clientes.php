<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Clientes extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat:make('Total Clientes, User::count()')
        ];
    }
}
