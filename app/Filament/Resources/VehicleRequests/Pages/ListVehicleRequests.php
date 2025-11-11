<?php

namespace App\Filament\Resources\VehicleRequests\Pages;

use App\Filament\Resources\VehicleRequests\VehicleRequestResource;
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

    /**
     * Modificar la consulta para filtrar solo las solicitudes del usuario autenticado
     * Esto es parte de la Fase 4: Vista de Usuario
     * 
     * Por ahora, todos los usuarios solo ven sus propias solicitudes.
     * Si necesitas que los administradores vean todas las solicitudes,
     * puedes agregar una verificación de rol aquí.
     */
    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->where('user_id', auth()->id())
            ->with(['vehicle', 'requestStatus', 'user']); // Cargar relaciones para mejor rendimiento
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
