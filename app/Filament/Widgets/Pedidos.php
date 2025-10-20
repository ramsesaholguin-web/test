<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class Pedidos extends ChartWidget
{
    protected ?string $heading = 'Pedidos';

    protected function getData(): array
    {
        return [
            Stat::make('Cantidad de Pedidos', 0)
                -> description('Total de pedidos realizados'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
