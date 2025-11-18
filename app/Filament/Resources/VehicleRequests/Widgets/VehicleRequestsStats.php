<?php

namespace App\Filament\Resources\VehicleRequests\Widgets;

use App\Models\RequestStatus;
use App\Models\VehicleRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VehicleRequestsStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // Obtener IDs de estados
        $pendingStatus = RequestStatus::where('name', 'Pending')->first();
        $approvedStatus = RequestStatus::where('name', 'Approved')->first();
        $rejectedStatus = RequestStatus::where('name', 'Rejected')->first();
        
        // Contadores
        $totalRequests = VehicleRequest::count();
        $pendingRequests = $pendingStatus ? VehicleRequest::where('request_status_id', $pendingStatus->id)->count() : 0;
        $approvedRequests = $approvedStatus ? VehicleRequest::where('request_status_id', $approvedStatus->id)->count() : 0;
        $rejectedRequests = $rejectedStatus ? VehicleRequest::where('request_status_id', $rejectedStatus->id)->count() : 0;
        
        // Solicitudes aprobadas este mes
        $approvedThisMonth = $approvedStatus ? VehicleRequest::where('request_status_id', $approvedStatus->id)
            ->whereMonth('approval_date', now()->month)
            ->whereYear('approval_date', now()->year)
            ->count() : 0;

        return [
            Stat::make('Total Requests', $totalRequests)
                ->description('All vehicle requests')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary')
                ->icon('heroicon-o-document-check'),
            
            Stat::make('Pending Requests', $pendingRequests)
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->icon('heroicon-o-clock')
                ->color($pendingRequests > 0 ? 'warning' : 'success'),
            
            Stat::make('Approved Requests', $approvedRequests)
                ->description('Approved requests')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
            
            Stat::make('Approved This Month', $approvedThisMonth)
                ->description('Approved in ' . now()->format('F'))
                ->descriptionIcon('heroicon-o-calendar')
                ->color('info')
                ->icon('heroicon-o-calendar-days'),
            
            Stat::make('Rejected Requests', $rejectedRequests)
                ->description('Rejected requests')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger')
                ->icon('heroicon-o-x-circle'),
        ];
    }
}

