<?php

namespace App\Filament\Resources\Vehicles\Widgets;

use App\Models\Vehicle;
use App\Models\VehicleRequest;
use App\Models\Maintenance;
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

        // Total de mantenimientos
        $totalMantenimientos = Maintenance::count();
        
        // Mantenimientos pendientes (próximos 30 días)
        $mantenimientosPendientes = Maintenance::whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '>=', now())
            ->where('next_maintenance_date', '<=', now()->addDays(30))
            ->count();

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
            
            Stat::make('Total Mantenimientos', $totalMantenimientos)
                ->description('Mantenimientos registrados')
                ->descriptionIcon('heroicon-o-wrench-screwdriver')
                ->color('info')
                ->icon('heroicon-o-wrench-screwdriver'),
            
            Stat::make('Mantenimientos Pendientes', $mantenimientosPendientes)
                ->description('Próximos 30 días')
                ->descriptionIcon('heroicon-o-calendar')
                ->color($mantenimientosPendientes > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-calendar'),
        ];
    }
}

