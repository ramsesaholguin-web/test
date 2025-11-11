<?php

namespace App\Filament\Resources\VehicleRequests\Tables;

use App\Models\RequestStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn ($record) => $record && $record->user_id === auth()->id()), // Solo puede editar sus propias solicitudes
            ])
            ->defaultSort('creation_date', 'desc')
            ->emptyStateHeading('No vehicle requests yet')
            ->emptyStateDescription('Create your first vehicle request to get started.')
            ->emptyStateIcon('heroicon-o-document-plus');
    }
}
