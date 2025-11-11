<?php

namespace App\Filament\Resources\VehicleRequests\Pages;

use App\Filament\Resources\VehicleRequests\VehicleRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVehicleRequest extends ViewRecord
{
    protected static string $resource = VehicleRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->visible(fn () => $this->record->user_id === auth()->id()), // Solo puede editar sus propias solicitudes
        ];
    }

    /**
     * Verificar que el usuario solo pueda ver sus propias solicitudes
     * Esto es parte de la Fase 4: Vista de Usuario
     * 
     * Este método se ejecuta cuando se carga la página de visualización
     */
    public function mount(int | string $record): void
    {
        parent::mount($record);
        
        // Verificar que el usuario solo pueda ver sus propias solicitudes
        // Si necesitas que los administradores puedan ver todas las solicitudes,
        // puedes agregar una verificación de rol aquí
        if ($this->record->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver esta solicitud.');
        }
    }
}
