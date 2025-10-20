<?php

namespace App\Filament\Resources\VehicleUsageHistories\Widgets;

use Filament\Widgets\ChartWidget;

class VehicleUsageHistories extends ChartWidget
{
    protected ?string $heading = 'Vehicle Usage Histories';

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
