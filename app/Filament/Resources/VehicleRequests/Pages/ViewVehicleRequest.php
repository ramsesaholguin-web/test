<?php

namespace App\Filament\Resources\VehicleRequests\Pages;

use App\Filament\Resources\VehicleRequests\VehicleRequestResource;
use App\Models\RequestStatus;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewVehicleRequest extends ViewRecord
{
    protected static string $resource = VehicleRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Acción para Aprobar Solicitud (solo visible para solicitudes pendientes)
            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve Vehicle Request')
                ->modalDescription(function () {
                    $userName = $this->record->user->name ?? 'user';
                    $vehiclePlate = $this->record->vehicle->plate ?? 'N/A';
                    return "Are you sure you want to approve the request from {$userName} for vehicle {$vehiclePlate}?";
                })
                ->form([
                    Textarea::make('approval_note')
                        ->label('Approval Note (Optional)')
                        ->placeholder('Add a note about this approval...')
                        ->rows(3)
                        ->maxLength(500),
                ])
                ->action(function (array $data) {
                    $this->approveRequest($data['approval_note'] ?? null);
                })
                ->visible(function () {
                    $pendingStatus = RequestStatus::where('name', 'Pending')->first();
                    return $pendingStatus && $this->record->request_status_id === $pendingStatus->id;
                }),
            
            // Acción para Rechazar Solicitud (solo visible para solicitudes pendientes)
            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Reject Vehicle Request')
                ->modalDescription(function () {
                    $userName = $this->record->user->name ?? 'user';
                    $vehiclePlate = $this->record->vehicle->plate ?? 'N/A';
                    return "Are you sure you want to reject the request from {$userName} for vehicle {$vehiclePlate}?";
                })
                ->form([
                    Textarea::make('approval_note')
                        ->label('Rejection Reason')
                        ->placeholder('Please provide a reason for rejecting this request...')
                        ->required()
                        ->rows(3)
                        ->maxLength(500)
                        ->helperText('A reason is required when rejecting a request.'),
                ])
                ->action(function (array $data) {
                    $this->rejectRequest($data['approval_note']);
                })
                ->visible(function () {
                    $pendingStatus = RequestStatus::where('name', 'Pending')->first();
                    return $pendingStatus && $this->record->request_status_id === $pendingStatus->id;
                }),
            
            // Acción para Cancelar Solicitud
            Action::make('cancel')
                ->label('Cancel')
                ->icon('heroicon-o-x-mark')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Cancel Vehicle Request')
                ->modalDescription(function () {
                    $userName = $this->record->user->name ?? 'user';
                    $vehiclePlate = $this->record->vehicle->plate ?? 'N/A';
                    $statusName = $this->record->requestStatus->name ?? 'N/A';
                    return "Are you sure you want to cancel the request from {$userName} for vehicle {$vehiclePlate}? Status: {$statusName}";
                })
                ->form([
                    Textarea::make('cancellation_reason')
                        ->label('Cancellation Reason')
                        ->placeholder('Please provide a reason for cancelling this request...')
                        ->required()
                        ->rows(3)
                        ->maxLength(500)
                        ->helperText('A reason is required when cancelling a request.'),
                ])
                ->action(function (array $data) {
                    $this->cancelRequest($data['cancellation_reason']);
                })
                ->visible(function () {
                    $pendingStatus = RequestStatus::where('name', 'Pending')->first();
                    $approvedStatus = RequestStatus::where('name', 'Approved')->first();
                    $cancelledStatus = RequestStatus::where('name', 'Cancelled')->first();
                    
                    // Usuarios solo pueden cancelar sus propias solicitudes pendientes
                    // Super Admins pueden cancelar pendientes o aprobadas
                    if (auth()->user()?->hasRole('super_admin')) {
                        return ($pendingStatus && $this->record->request_status_id === $pendingStatus->id) ||
                               ($approvedStatus && $this->record->request_status_id === $approvedStatus->id);
                    } else {
                        // Usuario regular solo puede cancelar sus propias solicitudes pendientes
                        return $pendingStatus && 
                               $this->record->request_status_id === $pendingStatus->id &&
                               $this->record->user_id === auth()->id();
                    }
                }),
            
            EditAction::make()
                ->visible(fn () => $this->record->user_id === auth()->id()), // Solo puede editar sus propias solicitudes
        ];
    }

    /**
     * Método para aprobar una solicitud
     * Actualiza el estado a "Approved" y registra quién y cuándo aprobó
     */
    protected function approveRequest(?string $note = null): void
    {
        $approvedStatus = RequestStatus::where('name', 'Approved')->first();
        $pendingStatus = RequestStatus::where('name', 'Pending')->first();
        
        // Validar que la solicitud esté pendiente
        if (!$pendingStatus || $this->record->request_status_id !== $pendingStatus->id) {
            Notification::make()
                ->title('Error')
                ->body('This request cannot be approved. It must be in "Pending" status.')
                ->danger()
                ->send();
            return;
        }
        
        // Verificar disponibilidad del vehículo antes de aprobar
        if (!$this->record->vehicle->isAvailableForDates(
            $this->record->requested_departure_date,
            $this->record->requested_return_date,
            $this->record->id // Excluir esta solicitud del chequeo
        )) {
            Notification::make()
                ->title('Vehicle Not Available')
                ->body('The vehicle is no longer available for the selected dates. Please check availability before approving.')
                ->danger()
                ->send();
            return;
        }
        
        // Actualizar solicitud
        $this->record->update([
            'request_status_id' => $approvedStatus->id,
            'approval_date' => now(),
            'approved_by' => auth()->id(),
            'approval_note' => $note,
        ]);
        
        Notification::make()
            ->title('Request Approved')
            ->body('The vehicle request has been approved successfully.')
            ->success()
            ->send();
        
        // Refrescar el registro para mostrar los cambios
        $this->refreshFormData(['record']);
    }

    /**
     * Método para rechazar una solicitud
     * Actualiza el estado a "Rejected" y registra quién, cuándo y por qué rechazó
     */
    protected function rejectRequest(string $reason): void
    {
        $rejectedStatus = RequestStatus::where('name', 'Rejected')->first();
        $pendingStatus = RequestStatus::where('name', 'Pending')->first();
        
        // Validar que la solicitud esté pendiente
        if (!$pendingStatus || $this->record->request_status_id !== $pendingStatus->id) {
            Notification::make()
                ->title('Error')
                ->body('This request cannot be rejected. It must be in "Pending" status.')
                ->danger()
                ->send();
            return;
        }
        
        // Actualizar solicitud
        $this->record->update([
            'request_status_id' => $rejectedStatus->id,
            'approval_date' => now(),
            'approved_by' => auth()->id(),
            'approval_note' => $reason,
        ]);
        
        Notification::make()
            ->title('Request Rejected')
            ->body('The vehicle request has been rejected. The reason has been saved.')
            ->success()
            ->send();
        
        // Refrescar el registro para mostrar los cambios
        $this->refreshFormData(['record']);
    }

    /**
     * Método para cancelar una solicitud
     * Actualiza el estado a "Cancelled" y registra quién, cuándo y por qué canceló
     */
    protected function cancelRequest(string $reason): void
    {
        $cancelledStatus = RequestStatus::where('name', 'Cancelled')->first();
        $pendingStatus = RequestStatus::where('name', 'Pending')->first();
        $approvedStatus = RequestStatus::where('name', 'Approved')->first();
        
        // Validar que la solicitud esté pendiente o aprobada
        if (!$pendingStatus || !$approvedStatus) {
            Notification::make()
                ->title('Error')
                ->body('Unable to cancel request. Required statuses not found.')
                ->danger()
                ->send();
            return;
        }
        
        $canCancel = false;
        if (auth()->user()?->hasRole('super_admin')) {
            // Super Admin puede cancelar pendientes o aprobadas
            $canCancel = ($this->record->request_status_id === $pendingStatus->id) ||
                        ($this->record->request_status_id === $approvedStatus->id);
        } else {
            // Usuario solo puede cancelar sus propias solicitudes pendientes
            $canCancel = ($this->record->request_status_id === $pendingStatus->id) &&
                        ($this->record->user_id === auth()->id());
        }
        
        if (!$canCancel) {
            Notification::make()
                ->title('Error')
                ->body('This request cannot be cancelled. Only pending requests can be cancelled by users, or admins can cancel pending/approved requests.')
                ->danger()
                ->send();
            return;
        }
        
        // Actualizar solicitud
        $this->record->update([
            'request_status_id' => $cancelledStatus->id,
            'approval_date' => now(),
            'approved_by' => auth()->id(),
            'approval_note' => $reason,
        ]);
        
        Notification::make()
            ->title('Request Cancelled')
            ->body('The vehicle request has been cancelled successfully.')
            ->success()
            ->send();
        
        // Refrescar el registro para mostrar los cambios
        $this->refreshFormData(['record']);
    }

    /**
     * Ya no restringimos la vista - todos pueden ver todas las solicitudes
     * Los botones de aprobar/rechazar están protegidos por la visibilidad
     */
    public function mount(int | string $record): void
    {
        parent::mount($record);
    }
}
