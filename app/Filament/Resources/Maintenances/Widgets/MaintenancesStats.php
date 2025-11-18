<?php

namespace App\Filament\Resources\Maintenances\Widgets;

use App\Models\Maintenance;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class MaintenancesStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalMaintenances = Maintenance::count();
        
        // Mantenimientos este mes
        $thisMonth = Maintenance::whereMonth('maintenance_date', now()->month)
            ->whereYear('maintenance_date', now()->year)
            ->count();
        
        // Costo total de mantenimientos este mes
        $totalCostThisMonth = Maintenance::whereMonth('maintenance_date', now()->month)
            ->whereYear('maintenance_date', now()->year)
            ->sum('cost') ?? 0;
        
        // Próximos mantenimientos (en los próximos 30 días)
        $upcomingMaintenances = Maintenance::whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '>=', now())
            ->where('next_maintenance_date', '<=', now()->addDays(30))
            ->count();
        
        // Mantenimientos vencidos (fecha pasada)
        $overdueMaintenances = Maintenance::whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '<', now())
            ->count();
        
        // Mantenimientos urgentes (en 7 días o menos)
        $urgentMaintenances = Maintenance::whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '>=', now())
            ->where('next_maintenance_date', '<=', now()->addDays(7))
            ->count();

        return [
            Stat::make('Total Maintenances', $totalMaintenances)
                ->description('All maintenance records')
                ->descriptionIcon('heroicon-o-wrench-screwdriver')
                ->color('primary')
                ->icon('heroicon-o-cog-6-tooth'),
            
            Stat::make('This Month', $thisMonth)
                ->description('Maintenances in ' . now()->format('F'))
                ->descriptionIcon('heroicon-o-calendar')
                ->color('info')
                ->icon('heroicon-o-calendar-days'),
            
            Stat::make('Total Cost This Month', '$' . number_format($totalCostThisMonth, 2))
                ->description('Maintenance costs in ' . now()->format('F'))
                ->descriptionIcon('heroicon-o-banknotes')
                ->color($totalCostThisMonth > 0 ? 'success' : 'gray')
                ->icon('heroicon-o-banknotes'),
            
            Stat::make('Upcoming Maintenances', $upcomingMaintenances)
                ->description('Next 30 days')
                ->descriptionIcon('heroicon-o-clock')
                ->color($upcomingMaintenances > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-calendar-days'),
            
            Stat::make('Urgent Maintenances', $urgentMaintenances)
                ->description('Next 7 days')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($urgentMaintenances > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-exclamation-triangle'),
            
            Stat::make('Overdue Maintenances', $overdueMaintenances)
                ->description('Past due date')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color($overdueMaintenances > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-x-circle'),
        ];
    }
}

