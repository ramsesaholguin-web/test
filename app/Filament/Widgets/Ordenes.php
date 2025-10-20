<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class Ordenes extends ChartWidget
{
    protected ?string $heading = 'Ordenes';

    protected function getData(): array
    {
        return [
            Stats::make('Ordenes por Mes', [
                'Enero' => 30,
                'Febrero' => 45,
                'Marzo' => 25,
                'Abril' => 60,
                'Mayo' => 50,
                'Junio' => 70,
            ])->color('blue'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
