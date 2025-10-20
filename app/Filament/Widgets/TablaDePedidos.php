<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TablaDePedidos extends ChartWidget
{
    protected ?string $heading = 'Tabla De Pedidos';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
