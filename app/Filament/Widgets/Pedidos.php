<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Pedidos extends StatsOverviewWidget
{
    protected ?string $heading = 'Pedidos';

    protected function getStats(): array
    {
        return [
            Stat::make('Cantidad de Pedidos', 0)
                ->description('Total de pedidos realizados'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
