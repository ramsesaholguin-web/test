<?php

namespace App\Filament\Resources\Vehicles\Tables;

use App\Models\RequestStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plate')
                    ->label('Plate')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                
                TextColumn::make('brand')
                    ->label('Brand')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('model')
                    ->label('Model')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('year')
                    ->label('Year')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('fuelType.name')
                    ->label('Fuel Type')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->placeholder('N/A'),
                
                TextColumn::make('status.name')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state ?? '')) {
                        'active' => 'success',
                        'maintenance' => 'warning',
                        'retired' => 'gray',
                        'sold' => 'danger',
                        'inactive' => 'gray',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match (strtolower($state ?? '')) {
                        'active' => 'heroicon-o-check-circle',
                        'maintenance' => 'heroicon-o-wrench-screwdriver',
                        'retired' => 'heroicon-o-x-circle',
                        'sold' => 'heroicon-o-archive-box',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->sortable(),
                
                // Columna de disponibilidad/uso actual
                TextColumn::make('current_usage')
                    ->label('Current Status')
                    ->state(function ($record) {
                        $activeStatus = \App\Models\VehicleStatus::where('name', 'Active')->first();
                        if (!$activeStatus || $record->status_id !== $activeStatus->id) {
                            return 'Not Available';
                        }
                        
                        $approvedStatus = RequestStatus::where('name', 'Approved')->first();
                        if (!$approvedStatus) {
                            return 'Available';
                        }
                        
                        // Buscar solicitudes aprobadas que estén en curso ahora
                        $currentRequest = $record->vehicleRequests()
                            ->where('request_status_id', $approvedStatus->id)
                            ->where('requested_departure_date', '<=', now())
                            ->where('requested_return_date', '>=', now())
                            ->with('user')
                            ->first();
                        
                        if ($currentRequest) {
                            $returnDate = Carbon::parse($currentRequest->requested_return_date);
                            return "In Use until {$returnDate->format('d/m/Y H:i')}";
                        }
                        
                        // Buscar próxima solicitud aprobada
                        $nextRequest = $record->vehicleRequests()
                            ->where('request_status_id', $approvedStatus->id)
                            ->where('requested_departure_date', '>', now())
                            ->orderBy('requested_departure_date', 'asc')
                            ->first();
                        
                        if ($nextRequest) {
                            $departureDate = Carbon::parse($nextRequest->requested_departure_date);
                            return "Available until {$departureDate->format('d/m/Y H:i')}";
                        }
                        
                        return 'Available';
                    })
                    ->badge()
                    ->color(function ($state) {
                        if (str_contains(strtolower($state ?? ''), 'in use')) {
                            return 'danger';
                        }
                        if (str_contains(strtolower($state ?? ''), 'available')) {
                            return 'success';
                        }
                        return 'gray';
                    })
                    ->icon(function ($state) {
                        if (str_contains(strtolower($state ?? ''), 'in use')) {
                            return 'heroicon-o-clock';
                        }
                        if (str_contains(strtolower($state ?? ''), 'available')) {
                            return 'heroicon-o-check-circle';
                        }
                        return 'heroicon-o-x-circle';
                    })
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('current_mileage')
                    ->label('Mileage')
                    ->numeric()
                    ->suffix(' km')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('current_location')
                    ->label('Location')
                    ->searchable()
                    ->icon('heroicon-o-map-pin')
                    ->toggleable(),
                
                TextColumn::make('vehicle_requests_count')
                    ->label('Requests')
                    ->counts('vehicleRequests')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('maintenances_count')
                    ->label('Maintenances')
                    ->counts('maintenances')
                    ->badge()
                    ->color('warning')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('last_maintenance_date')
                    ->label('Last Maintenance')
                    ->state(function ($record) {
                        $lastMaintenance = $record->maintenances()->latest('maintenance_date')->first();
                        if (!$lastMaintenance || !$lastMaintenance->maintenance_date) {
                            return 'N/A';
                        }
                        return Carbon::parse($lastMaintenance->maintenance_date)->format('d/m/Y');
                    })
                    ->sortable(query: function ($query, string $direction) {
                        return $query->orderByRaw("(
                            SELECT MAX(maintenance_date) 
                            FROM maintenances 
                            WHERE vehicle_id = vehicles.id
                        ) {$direction}");
                    })
                    ->toggleable(),
                
                TextColumn::make('registration_date')
                    ->label('Registration')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('belongsTo')
                    ->label('Belongs To')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtro por Estado del Vehículo
                SelectFilter::make('status_id')
                    ->label('Vehicle Status')
                    ->relationship('status', 'name')
                    ->preload()
                    ->searchable()
                    ->multiple(),
                
                // Filtro por Tipo de Combustible
                SelectFilter::make('fuel_type_id')
                    ->label('Fuel Type')
                    ->relationship('fuelType', 'name')
                    ->preload()
                    ->searchable(),
                
                // Filtro por Marca
                SelectFilter::make('brand')
                    ->label('Brand')
                    ->options(function () {
                        return \App\Models\Vehicle::query()
                            ->distinct()
                            ->orderBy('brand')
                            ->pluck('brand', 'brand')
                            ->toArray();
                    })
                    ->searchable()
                    ->multiple(),
                
                // Filtro por Ubicación
                SelectFilter::make('current_location')
                    ->label('Location')
                    ->options(function () {
                        return \App\Models\Vehicle::query()
                            ->whereNotNull('current_location')
                            ->distinct()
                            ->orderBy('current_location')
                            ->pluck('current_location', 'current_location')
                            ->toArray();
                    })
                    ->searchable()
                    ->multiple(),
                
                // Filtro por Disponibilidad (vehículos disponibles/ocupados)
                SelectFilter::make('availability')
                    ->label('Availability')
                    ->options([
                        'available' => 'Available',
                        'in_use' => 'Currently In Use',
                        'with_upcoming' => 'With Upcoming Requests',
                    ])
                    ->query(function ($query, array $data) {
                        $activeStatus = \App\Models\VehicleStatus::where('name', 'Active')->first();
                        $approvedStatus = RequestStatus::where('name', 'Approved')->first();
                        
                        if (!$activeStatus || !$approvedStatus) {
                            return $query;
                        }
                        
                        return match ($data['value'] ?? null) {
                            'available' => $query->where('status_id', $activeStatus->id)
                                ->whereDoesntHave('vehicleRequests', function ($q) use ($approvedStatus) {
                                    $q->where('request_status_id', $approvedStatus->id)
                                      ->where('requested_departure_date', '<=', now())
                                      ->where('requested_return_date', '>=', now());
                                }),
                            
                            'in_use' => $query->where('status_id', $activeStatus->id)
                                ->whereHas('vehicleRequests', function ($q) use ($approvedStatus) {
                                    $q->where('request_status_id', $approvedStatus->id)
                                      ->where('requested_departure_date', '<=', now())
                                      ->where('requested_return_date', '>=', now());
                                }),
                            
                            'with_upcoming' => $query->where('status_id', $activeStatus->id)
                                ->whereHas('vehicleRequests', function ($q) use ($approvedStatus) {
                                    $q->where('request_status_id', $approvedStatus->id)
                                      ->where('requested_departure_date', '>', now());
                                }),
                            
                            default => $query,
                        };
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
