<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class Ordenes extends ChartWidget
{
    protected ?string $heading = 'Ordenes';

    protected function getData(): array
    {
        return [
            Stats::make('Ordenes', 0)
                ->description('Total de órdenes procesadas'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
