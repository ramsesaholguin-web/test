<?php

namespace App\Filament\Widgets;

use App\Models\VehicleRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Pedidos extends StatsOverviewWidget
{
    protected ?string $heading = 'Solicitudes de Vehículos';

    protected function getStats(): array
    {
        $totalSolicitudes = VehicleRequest::count();
        
        $solicitudesEsteMes = VehicleRequest::whereMonth('creation_date', now()->month)
            ->whereYear('creation_date', now()->year)
            ->count();
        
        $solicitudesHoy = VehicleRequest::whereDate('creation_date', today())
            ->count();

        return [
            Stat::make('Total de Solicitudes', $totalSolicitudes)
                ->description('Todas las solicitudes registradas')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary')
                ->icon('heroicon-o-document-text'),
            
            Stat::make('Solicitudes Este Mes', $solicitudesEsteMes)
                ->description('Solicitudes del mes actual')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('info')
                ->icon('heroicon-o-calendar'),
            
            Stat::make('Solicitudes Hoy', $solicitudesHoy)
                ->description('Solicitudes creadas hoy')
                ->descriptionIcon('heroicon-o-clock')
                ->color('success')
                ->icon('heroicon-o-clock'),
        ];
    }
}

