<?php

namespace App\Filament\Resources\VehicleRequests\Pages;

use App\Filament\Resources\VehicleRequests\VehicleRequestResource;
use App\Filament\Resources\VehicleRequests\Widgets\VehicleRequestsStats;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListVehicleRequests extends ListRecords
{
    protected static string $resource = VehicleRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        // Solo super_admins pueden ver los widgets
        if (!auth()->user()?->hasRole('super_admin')) {
            return [];
        }
        
        return [
            VehicleRequestsStats::class,
        ];
    }

    /**
     * Modificar la consulta para filtrar por usuario si no es admin
     * Los usuarios normales solo ven sus propias solicitudes
     * Los administradores pueden ver todas las solicitudes para aprobar/rechazar
     */
    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery()
            ->with(['vehicle', 'requestStatus', 'user', 'approvedBy']); // Cargar relaciones para mejor rendimiento
        
        // Si el usuario no es super_admin, filtrar solo sus solicitudes
        if (!auth()->user()?->hasRole('super_admin')) {
            $query->where('user_id', auth()->id());
        }
        
        return $query;
    }

    /**
     * Manejar el caso cuando no hay registros
     */
    protected function getEmptyStateDescription(): ?string
    {
        return 'Create your first vehicle request to get started.';
    }

    protected function getEmptyStateHeading(): ?string
    {
        return 'No vehicle requests yet';
    }
}
