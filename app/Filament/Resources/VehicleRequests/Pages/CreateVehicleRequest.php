<?php

namespace App\Filament\Resources\VehicleRequests\Pages;

use App\Filament\Resources\VehicleRequests\VehicleRequestResource;
use App\Models\RequestStatus;
use App\Models\VehicleRequest;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateVehicleRequest extends CreateRecord
{
    protected static string $resource = VehicleRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Validar que el usuario esté autenticado
        if (!auth()->check()) {
            throw new \Illuminate\Auth\AuthenticationException('Debe estar autenticado para crear una solicitud.');
        }

        // Set user_id to current authenticated user
        $data['user_id'] = auth()->id();
        
        // Set creation_date to now
        $data['creation_date'] = Carbon::now();
        
        // Set status to "Pending" if not set
        if (!isset($data['request_status_id'])) {
            $pendingStatus = RequestStatus::where('name', 'Pending')->first();
            if ($pendingStatus) {
                $data['request_status_id'] = $pendingStatus->id;
            }
        }
        
        // Set belongsTo if needed (you can customize this)
        if (!isset($data['belongsTo']) || empty($data['belongsTo'])) {
            $data['belongsTo'] = auth()->user()->name ?? 'System';
        }

        // ===== VALIDACIONES DEL SERVIDOR (Fase 3) =====
        // Estas validaciones se ejecutan ANTES de crear el registro
        // y lanzan excepciones de validación si fallan
        
        // Parsear fechas
        $departureDate = Carbon::parse($data['requested_departure_date']);
        $returnDate = Carbon::parse($data['requested_return_date']);

        // Validación 1: Fechas no pueden estar en el pasado
        VehicleRequest::validateDatesNotInPast($departureDate);

        // Validación 2: Fecha de retorno debe ser posterior a la de salida
        VehicleRequest::validateReturnDateAfterDeparture($departureDate, $returnDate);

        // Validación 3: Rango de fechas razonable (máximo 90 días)
        VehicleRequest::validateDateRange($departureDate, $returnDate, 90);

        // Validación 4: Vehículo debe estar disponible
        VehicleRequest::validateVehicleAvailability(
            $data['vehicle_id'], 
            $departureDate, 
            $returnDate
        );

        // Validación 5: No puede tener múltiples solicitudes pendientes para el mismo vehículo y fechas
        VehicleRequest::validateNoDuplicatePendingRequests(
            auth()->id(),
            $data['vehicle_id'],
            $departureDate,
            $returnDate
        );
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
