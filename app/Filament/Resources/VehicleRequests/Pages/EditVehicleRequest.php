<?php

namespace App\Filament\Resources\VehicleRequests\Pages;

use App\Filament\Resources\VehicleRequests\VehicleRequestResource;
use App\Models\RequestStatus;
use App\Models\VehicleRequest;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Carbon;

class EditVehicleRequest extends EditRecord
{
    protected static string $resource = VehicleRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->visible(fn () => $this->record->user_id === auth()->id()), // Solo puede eliminar sus propias solicitudes
        ];
    }

    /**
     * Verificar que el usuario solo pueda editar sus propias solicitudes
     * Esto es parte de la Fase 4: Vista de Usuario
     * 
     * Este método se ejecuta cuando se carga la página de edición
     */
    public function mount(int | string $record): void
    {
        parent::mount($record);
        
        // Verificar que el usuario solo pueda editar sus propias solicitudes
        // Si necesitas que los administradores puedan editar todas las solicitudes,
        // puedes agregar una verificación de rol aquí
        if ($this->record->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar esta solicitud.');
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Add current request ID to form data so the vehicle selector can exclude it from availability check
        $data['_current_request_id'] = $this->record->id;
        return $data;
    }

    /**
     * Hook que se ejecuta ANTES de actualizar el registro
     * Aquí validamos todas las reglas de negocio del servidor
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->record;

        // Obtener el estado actual de la solicitud
        $currentStatus = $record->requestStatus;
        $approvedStatus = RequestStatus::where('name', 'Approved')->first();
        $rejectedStatus = RequestStatus::where('name', 'Rejected')->first();

        // Validación: Solicitudes aprobadas o rechazadas no pueden ser editadas (solo canceladas)
        if ($currentStatus && 
            (($approvedStatus && $currentStatus->id == $approvedStatus->id) || 
             ($rejectedStatus && $currentStatus->id == $rejectedStatus->id))) {
            
            // Permitir editar solo si está cambiando el estado (por ejemplo, a Cancelada)
            $newStatusId = $data['request_status_id'] ?? $record->request_status_id;
            $newStatus = RequestStatus::find($newStatusId);
            
            // Si el nuevo estado sigue siendo Aprobada o Rechazada, no permitir editar otros campos
            if ($newStatus && 
                (($approvedStatus && $newStatus->id == $approvedStatus->id) || 
                 ($rejectedStatus && $newStatus->id == $rejectedStatus->id))) {
                
                // Verificar si se están cambiando campos críticos (fechas, vehículo)
                if (isset($data['requested_departure_date']) || 
                    isset($data['requested_return_date']) || 
                    isset($data['vehicle_id'])) {
                    
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'vehicle_id' => ['No se pueden modificar las fechas o el vehículo de una solicitud aprobada o rechazada.'],
                    ]);
                }
            }
        }

        // Si se están modificando las fechas o el vehículo, validar disponibilidad
        if (isset($data['requested_departure_date']) && isset($data['requested_return_date']) && isset($data['vehicle_id'])) {
            $departureDate = Carbon::parse($data['requested_departure_date']);
            $returnDate = Carbon::parse($data['requested_return_date']);

            // Validación 1: Fechas no pueden estar en el pasado
            VehicleRequest::validateDatesNotInPast($departureDate);

            // Validación 2: Fecha de retorno debe ser posterior a la de salida
            VehicleRequest::validateReturnDateAfterDeparture($departureDate, $returnDate);

            // Validación 3: Rango de fechas razonable (máximo 90 días)
            VehicleRequest::validateDateRange($departureDate, $returnDate, 90);

            // Validación 4: Vehículo debe estar disponible (excluir esta solicitud del chequeo)
            VehicleRequest::validateVehicleAvailability(
                $data['vehicle_id'], 
                $departureDate, 
                $returnDate,
                $record->id // Excluir esta solicitud del chequeo de disponibilidad
            );

            // Validación 5: No puede tener múltiples solicitudes pendientes para el mismo vehículo y fechas
            VehicleRequest::validateNoDuplicatePendingRequests(
                $record->user_id,
                $data['vehicle_id'],
                $departureDate,
                $returnDate,
                $record->id // Excluir esta solicitud del chequeo
            );
        }

        return $data;
    }
}
