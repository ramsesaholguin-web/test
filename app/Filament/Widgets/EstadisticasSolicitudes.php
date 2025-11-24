<?php

namespace App\Filament\Widgets;

use App\Models\VehicleRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EstadisticasSolicitudes extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 3;

    protected ?string $heading = 'Estadísticas de Solicitudes';

    protected function getStats(): array
    {
        $totalSolicitudes = VehicleRequest::count();
        
        $solicitudesPendientes = VehicleRequest::whereHas('requestStatus', function ($query) {
            $query->where('name', 'like', '%pendiente%');
        })->count();
        
        $solicitudesAprobadas = VehicleRequest::whereHas('requestStatus', function ($query) {
            $query->where('name', 'like', '%aprobado%');
        })->count();
        
        $solicitudesRechazadas = VehicleRequest::whereHas('requestStatus', function ($query) {
            $query->where('name', 'like', '%rechazado%');
        })->count();

        return [
            Stat::make('Total de Solicitudes', $totalSolicitudes)
                ->description('Todas las solicitudes registradas')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary')
                ->icon('heroicon-o-document-text'),
            
            Stat::make('Solicitudes Pendientes', $solicitudesPendientes)
                ->description('Esperando aprobación')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->icon('heroicon-o-clock'),
            
            Stat::make('Solicitudes Aprobadas', $solicitudesAprobadas)
                ->description('Aprobadas y en proceso')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
            
            Stat::make('Solicitudes Rechazadas', $solicitudesRechazadas)
                ->description('Solicitudes rechazadas')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger')
                ->icon('heroicon-o-x-circle'),
        ];
    }
}

