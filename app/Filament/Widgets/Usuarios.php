<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Usuarios extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalUsuarios = User::count();

        return [
            Stat::make('Total de Usuarios', $totalUsuarios)
                ->description('Usuarios registrados en el sistema')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary')
                ->icon('heroicon-o-user-group')
                ->url(\App\Filament\Resources\Users\UserResource::getUrl('index')),
        ];
    }
}
