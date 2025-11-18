<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use App\Models\RequestStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class VehicleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Sección 1: Información Básica
                Section::make('Basic Information')
                    ->schema([
                        TextEntry::make('plate')
                            ->label('License Plate')
                            ->icon('heroicon-o-truck')
                            ->weight('medium')
                            ->copyable(),
                        
                        TextEntry::make('brand')
                            ->label('Brand')
                            ->icon('heroicon-o-building-office'),
                        
                        TextEntry::make('model')
                            ->label('Model')
                            ->icon('heroicon-o-cog-6-tooth'),
                        
                TextEntry::make('year')
                            ->label('Year')
                            ->numeric()
                            ->icon('heroicon-o-calendar'),
                    ])
                    ->columns(4),
                
                // Sección 2: Especificaciones
                Section::make('Specifications')
                    ->schema([
                        TextEntry::make('vin')
                            ->label('VIN')
                            ->icon('heroicon-o-finger-print')
                            ->copyable()
                            ->columnSpan(1),
                        
                TextEntry::make('fuelType.name')
                    ->label('Fuel Type')
                    ->badge()
                            ->color('info')
                            ->icon('heroicon-o-bolt')
                            ->placeholder('Not set')
                            ->columnSpan(1),
                        
                TextEntry::make('current_mileage')
                            ->label('Current Mileage')
                    ->numeric()
                            ->suffix(' km')
                            ->icon('heroicon-o-chart-bar')
                            ->columnSpan(1),
                        
                TextEntry::make('status.name')
                    ->label('Vehicle Status')
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
                            ->placeholder('Not set')
                            ->columnSpan(1),
                    ])
                    ->columns(4),
                
                // Sección 3: Estado Actual y Disponibilidad
                Section::make('Current Status & Availability')
                    ->schema([
                        TextEntry::make('availability_status')
                            ->label('Availability Status')
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
                                    $userName = $currentRequest->user->name ?? 'Unknown';
                                    return "In Use by {$userName} until {$returnDate->format('d/m/Y H:i')}";
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
                            ->columnSpan(1),
                        
                        TextEntry::make('current_location')
                            ->label('Current Location')
                            ->icon('heroicon-o-map-pin')
                            ->placeholder('Not specified')
                            ->columnSpan(1),
                        
                        TextEntry::make('next_availability')
                            ->label('Next Available Date')
                            ->state(function ($record) {
                                $activeStatus = \App\Models\VehicleStatus::where('name', 'Active')->first();
                                $approvedStatus = RequestStatus::where('name', 'Approved')->first();
                                
                                if (!$activeStatus || !$approvedStatus || $record->status_id !== $activeStatus->id) {
                                    return 'N/A';
                                }
                                
                                // Buscar próxima solicitud aprobada
                                $nextRequest = $record->vehicleRequests()
                                    ->where('request_status_id', $approvedStatus->id)
                                    ->where('requested_departure_date', '>', now())
                                    ->orderBy('requested_departure_date', 'asc')
                                    ->first();
                                
                                if ($nextRequest) {
                                    $departureDate = Carbon::parse($nextRequest->requested_departure_date);
                                    return $departureDate->format('d/m/Y H:i');
                                }
                                
                                return 'Available now';
                            })
                            ->icon('heroicon-o-calendar-days')
                            ->placeholder('N/A')
                            ->columnSpan(1),
                    ])
                    ->columns(3),
                
                // Sección 4: Próximas Solicitudes Aprobadas
                Section::make('Upcoming Approved Requests')
                    ->schema([
                        TextEntry::make('upcoming_requests')
                            ->label('Upcoming Requests')
                            ->state(function ($record) {
                                $approvedStatus = RequestStatus::where('name', 'Approved')->first();
                                if (!$approvedStatus) {
                                    return [];
                                }
                                
                                $upcoming = $record->vehicleRequests()
                                    ->where('request_status_id', $approvedStatus->id)
                                    ->where('requested_departure_date', '>', now())
                                    ->orderBy('requested_departure_date', 'asc')
                                    ->limit(5)
                                    ->with('user')
                                    ->get();
                                
                                if ($upcoming->isEmpty()) {
                                    return 'No upcoming requests';
                                }
                                
                                return $upcoming->map(function ($request) {
                                    $departure = Carbon::parse($request->requested_departure_date);
                                    $return = Carbon::parse($request->requested_return_date);
                                    $userName = $request->user->name ?? 'Unknown';
                                    return "{$departure->format('d/m/Y H:i')} - {$return->format('d/m/Y H:i')} ({$userName})";
                                })->join(' | ');
                            })
                            ->placeholder('No upcoming requests')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->visible(fn ($record) => $record->vehicleRequests()
                        ->whereHas('requestStatus', fn ($q) => $q->where('name', 'Approved'))
                        ->where('requested_departure_date', '>', now())
                        ->exists()),
                
                // Sección 5: Mantenimiento
                Section::make('Maintenance Information')
                    ->schema([
                        TextEntry::make('last_maintenance_date')
                            ->label('Last Maintenance')
                            ->state(function ($record) {
                                $lastMaintenance = $record->maintenances()->latest('maintenance_date')->first();
                                if (!$lastMaintenance || !$lastMaintenance->maintenance_date) {
                                    return 'No maintenance records';
                                }
                                return Carbon::parse($lastMaintenance->maintenance_date)->format('d/m/Y');
                            })
                            ->icon('heroicon-o-wrench-screwdriver')
                            ->placeholder('No maintenance records'),
                        
                        TextEntry::make('maintenances_count')
                            ->label('Total Maintenances')
                            ->numeric()
                            ->badge()
                            ->color('warning')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->formatStateUsing(fn ($state, $record) => $record->maintenances()->count()),
                        
                        TextEntry::make('next_maintenance_date')
                            ->label('Next Scheduled Maintenance')
                            ->state(function ($record) {
                                $nextMaintenance = $record->maintenances()
                                    ->whereNotNull('next_maintenance_date')
                                    ->where('next_maintenance_date', '>', now())
                                    ->orderBy('next_maintenance_date', 'asc')
                                    ->first();
                                
                                if (!$nextMaintenance || !$nextMaintenance->next_maintenance_date) {
                                    return 'Not scheduled';
                                }
                                
                                return Carbon::parse($nextMaintenance->next_maintenance_date)->format('d/m/Y');
                            })
                            ->icon('heroicon-o-calendar-days')
                            ->color(function ($state) {
                                if ($state && $state !== 'Not scheduled') {
                                    try {
                                        $date = Carbon::parse($state);
                                        $daysUntil = $date->diffInDays(now());
                                        if ($daysUntil <= 7) {
                                            return 'danger';
                                        }
                                        if ($daysUntil <= 30) {
                                            return 'warning';
                                        }
                                    } catch (\Exception $e) {
                                        // Si no se puede parsear la fecha, no aplicar color
                                        return null;
                                    }
                                }
                                return null;
                            })
                            ->placeholder('Not scheduled'),
                    ])
                    ->columns(3),
                
                // Sección 6: Estadísticas
                Section::make('Statistics')
                    ->schema([
                        TextEntry::make('vehicle_requests_count')
                            ->label('Total Requests')
                            ->numeric()
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-o-document-check')
                            ->formatStateUsing(fn ($state, $record) => $record->vehicleRequests()->count()),
                        
                        TextEntry::make('approved_requests_count')
                            ->label('Approved Requests')
                            ->numeric()
                            ->badge()
                            ->color('success')
                            ->icon('heroicon-o-check-circle')
                            ->formatStateUsing(function ($state, $record) {
                                $approvedStatus = RequestStatus::where('name', 'Approved')->first();
                                if (!$approvedStatus) {
                                    return 0;
                                }
                                return $record->vehicleRequests()
                                    ->where('request_status_id', $approvedStatus->id)
                                    ->count();
                            }),
                        
                        TextEntry::make('vehicle_documents_count')
                            ->label('Documents')
                            ->numeric()
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-o-document')
                            ->formatStateUsing(fn ($state, $record) => $record->vehicleDocuments()->count()),
                    ])
                    ->columns(3),
                
                // Sección 7: Información Adicional
                Section::make('Additional Information')
                    ->schema([
                TextEntry::make('registration_date')
                            ->label('Registration Date')
                            ->dateTime('d/m/Y')
                            ->icon('heroicon-o-calendar')
                            ->columnSpan(1),
                        
                        TextEntry::make('belongsTo')
                            ->label('Belongs To')
                            ->icon('heroicon-o-building-office')
                            ->placeholder('Not specified')
                            ->columnSpan(1),
                        
                        TextEntry::make('note')
                            ->label('Notes')
                            ->placeholder('No notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                // Sección 8: Fechas del Sistema
                Section::make('System Dates')
                    ->schema([
                TextEntry::make('created_at')
                            ->label('Record Created')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-clock')
                            ->columnSpan(1),
                        
                TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-arrow-path')
                            ->since()
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
