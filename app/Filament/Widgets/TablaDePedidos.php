<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TablaDePedidos extends ChartWidget
{
    protected ?string $heading = 'Tabla De Pedidos';

    protected function getData(): array
    {
            return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
