<?php

namespace App\Filament\Resources\VehicleRequests\Tables;

use App\Models\RequestStatus;
use App\Models\VehicleRequest;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class VehicleRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Columna de Estado con Badge (colorido)
                TextColumn::make('requestStatus.name')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        'Cancelled' => 'gray',
                        'Completed' => 'info',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),
                
                // Columna de Vehículo (con información completa)
                TextColumn::make('vehicle.plate')
                    ->label('Vehicle')
                    ->formatStateUsing(fn ($record) => $record && $record->vehicle 
                        ? "{$record->vehicle->plate} - {$record->vehicle->brand} {$record->vehicle->model}"
                        : 'N/A'
                    )
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('vehicle', function ($q) use ($search) {
                            $q->where('plate', 'like', "%{$search}%")
                              ->orWhere('brand', 'like', "%{$search}%")
                              ->orWhere('model', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                
                // Columna de Fechas de Solicitud
                TextColumn::make('requested_departure_date')
                    ->label('Departure')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->description(fn ($record) => $record && $record->requested_return_date 
                        ? 'Return: ' . Carbon::parse($record->requested_return_date)->format('d/m/Y H:i')
                        : null
                    ),
                
                // Columna de Destino
                TextColumn::make('destination')
                    ->label('Destination')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record ? $record->destination : null),
                
                // Columna de Evento
                TextColumn::make('event')
                    ->label('Event')
                    ->searchable()
                    ->limit(20)
                    ->toggleable(),
                
                // Columna de Usuario (solo para admins)
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                // Columna de Fecha de Creación
                TextColumn::make('creation_date')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                
                // Columna de Fecha de Aprobación
                TextColumn::make('approval_date')
                    ->label('Approved')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$state) {
                            return '-';
                        }
                        try {
                            return Carbon::parse($state)->format('d/m/Y H:i');
                        } catch (\Exception $e) {
                            return '-';
                        }
                    })
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                // Filtro por Estado
                SelectFilter::make('request_status_id')
                    ->label('Status')
                    ->relationship('requestStatus', 'name')
                    ->preload()
                    ->multiple(),
                
                // Filtro por Vehículo
                SelectFilter::make('vehicle_id')
                    ->label('Vehicle')
                    ->relationship('vehicle', 'plate')
                    ->searchable()
                    ->preload(),
                
                // Filtro por Usuario (para que usuarios normales vean solo sus solicitudes)
                SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                
                // Acción para Aprobar Solicitud
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Vehicle Request')
                    ->modalDescription(fn ($record) => "Are you sure you want to approve the request from " . ($record->user->name ?? 'user') . " for vehicle " . ($record->vehicle->plate ?? 'N/A') . "?")
                    ->form([
                        Textarea::make('approval_note')
                            ->label('Approval Note (Optional)')
                            ->placeholder('Add a note about this approval...')
                            ->rows(3)
                            ->maxLength(500),
                    ])
                    ->action(function (VehicleRequest $record, array $data) {
                        // Obtener estados
                        $approvedStatus = RequestStatus::where('name', 'Approved')->first();
                        $pendingStatus = RequestStatus::where('name', 'Pending')->first();
                        
                        // Validar que la solicitud esté pendiente
                        if (!$pendingStatus || $record->request_status_id !== $pendingStatus->id) {
                            Notification::make()
                                ->title('Error')
                                ->body('This request cannot be approved. It must be in "Pending" status.')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        // Verificar disponibilidad del vehículo antes de aprobar
                        if (!$record->vehicle->isAvailableForDates(
                            $record->requested_departure_date,
                            $record->requested_return_date,
                            $record->id // Excluir esta solicitud del chequeo
                        )) {
                            Notification::make()
                                ->title('Vehicle Not Available')
                                ->body('The vehicle is no longer available for the selected dates. Please check availability before approving.')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        // Actualizar solicitud
                        $record->update([
                            'request_status_id' => $approvedStatus->id,
                            'approval_date' => now(),
                            'approved_by' => auth()->id(),
                            'approval_note' => $data['approval_note'] ?? null,
                        ]);
                        
                        Notification::make()
                            ->title('Request Approved')
                            ->body("The vehicle request has been approved successfully.")
                            ->success()
                            ->send();
                    })
                    ->visible(function ($record) {
                        if (!$record) return false;
                        $pendingStatus = RequestStatus::where('name', 'Pending')->first();
                        return $pendingStatus && $record->request_status_id === $pendingStatus->id;
                    }),
                
                // Acción para Rechazar Solicitud
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Vehicle Request')
                    ->modalDescription(fn ($record) => "Are you sure you want to reject the request from " . ($record->user->name ?? 'user') . " for vehicle " . ($record->vehicle->plate ?? 'N/A') . "?")
                    ->form([
                        Textarea::make('approval_note')
                            ->label('Rejection Reason')
                            ->placeholder('Please provide a reason for rejecting this request...')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('A reason is required when rejecting a request.'),
                    ])
                    ->action(function (VehicleRequest $record, array $data) {
                        // Obtener estados
                        $rejectedStatus = RequestStatus::where('name', 'Rejected')->first();
                        $pendingStatus = RequestStatus::where('name', 'Pending')->first();
                        
                        // Validar que la solicitud esté pendiente
                        if (!$pendingStatus || $record->request_status_id !== $pendingStatus->id) {
                            Notification::make()
                                ->title('Error')
                                ->body('This request cannot be rejected. It must be in "Pending" status.')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        // Actualizar solicitud
                        $record->update([
                            'request_status_id' => $rejectedStatus->id,
                            'approval_date' => now(),
                            'approved_by' => auth()->id(),
                            'approval_note' => $data['approval_note'],
                        ]);
                        
                        Notification::make()
                            ->title('Request Rejected')
                            ->body("The vehicle request has been rejected. The reason has been saved.")
                            ->success()
                            ->send();
                    })
                    ->visible(function ($record) {
                        if (!$record) return false;
                        $pendingStatus = RequestStatus::where('name', 'Pending')->first();
                        return $pendingStatus && $record->request_status_id === $pendingStatus->id;
                    }),
                
                // Acción para Cancelar Solicitud
                Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-mark')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Vehicle Request')
                    ->modalDescription(fn ($record) => "Are you sure you want to cancel the request from " . ($record->user->name ?? 'user') . " for vehicle " . ($record->vehicle->plate ?? 'N/A') . "?")
                    ->form([
                        Textarea::make('cancellation_reason')
                            ->label('Cancellation Reason')
                            ->placeholder('Please provide a reason for cancelling this request...')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('A reason is required when cancelling a request.'),
                    ])
                    ->action(function (VehicleRequest $record, array $data) {
                        // Obtener estados
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
                            $canCancel = ($record->request_status_id === $pendingStatus->id) ||
                                        ($record->request_status_id === $approvedStatus->id);
                        } else {
                            // Usuario solo puede cancelar sus propias solicitudes pendientes
                            $canCancel = ($record->request_status_id === $pendingStatus->id) &&
                                        ($record->user_id === auth()->id());
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
                        $record->update([
                            'request_status_id' => $cancelledStatus->id,
                            'approval_date' => now(),
                            'approved_by' => auth()->id(),
                            'approval_note' => $data['cancellation_reason'],
                        ]);
                        
                        Notification::make()
                            ->title('Request Cancelled')
                            ->body('The vehicle request has been cancelled successfully.')
                            ->success()
                            ->send();
                    })
                    ->visible(function ($record) {
                        if (!$record) return false;
                        $pendingStatus = RequestStatus::where('name', 'Pending')->first();
                        $approvedStatus = RequestStatus::where('name', 'Approved')->first();
                        $cancelledStatus = RequestStatus::where('name', 'Cancelled')->first();
                        
                        if (!$pendingStatus || !$approvedStatus) return false;
                        
                        // Usuarios solo pueden cancelar sus propias solicitudes pendientes
                        // Super Admins pueden cancelar pendientes o aprobadas
                        if (auth()->user()?->hasRole('super_admin')) {
                            return ($record->request_status_id === $pendingStatus->id) ||
                                   ($record->request_status_id === $approvedStatus->id);
                        } else {
                            return $pendingStatus && 
                                   $record->request_status_id === $pendingStatus->id &&
                                   $record->user_id === auth()->id();
                        }
                    }),
                
                EditAction::make()
                    ->visible(fn ($record) => $record && $record->user_id === auth()->id()), // Solo puede editar sus propias solicitudes
            ])
            ->defaultSort('creation_date', 'desc')
            ->emptyStateHeading('No vehicle requests yet')
            ->emptyStateDescription('Create your first vehicle request to get started.')
            ->emptyStateIcon('heroicon-o-document-plus');
    }
}
