<?php

namespace App\Filament\Resources\Vehicles\Widgets;

use App\Models\Vehicle;
use App\Models\VehicleRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VehiculosStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalVehiculos = Vehicle::count();
        
        // Vehículos disponibles (no tienen solicitudes activas)
        $vehiculosDisponibles = Vehicle::whereDoesntHave('vehicleRequests', function ($query) {
            $query->where('requested_departure_date', '<=', now())
                  ->where('requested_return_date', '>=', now())
                  ->whereHas('requestStatus', function ($q) {
                      $q->where('name', 'like', '%aprobado%');
                  });
        })->count();
        
        // Vehículos en uso actualmente
        $vehiculosEnUso = Vehicle::whereHas('vehicleRequests', function ($query) {
            $query->where('requested_departure_date', '<=', now())
                  ->where('requested_return_date', '>=', now())
                  ->whereHas('requestStatus', function ($q) {
                      $q->where('name', 'like', '%aprobado%');
                  });
        })->count();

        return [
            Stat::make('Total de Vehículos', $totalVehiculos)
                ->description('Vehículos registrados en el sistema')
                ->descriptionIcon('heroicon-o-truck')
                ->color('primary')
                ->icon('heroicon-o-truck'),
            
            Stat::make('Vehículos Disponibles', $vehiculosDisponibles)
                ->description('Vehículos disponibles para solicitar')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
            
            Stat::make('Vehículos en Uso', $vehiculosEnUso)
                ->description('Vehículos actualmente en uso')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->icon('heroicon-o-clock'),
        ];
    }
}

