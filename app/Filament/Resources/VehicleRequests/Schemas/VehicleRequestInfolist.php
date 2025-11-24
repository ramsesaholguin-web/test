<?php

namespace App\Filament\Resources\VehicleRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VehicleRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Sección 1: Información de la Solicitud
                Section::make('Request Information')
                    ->schema([
                        TextEntry::make('requestStatus.name')
                            ->label('Status')
                            ->badge()
                            ->color(function ($state) {
                                return match (strtolower($state ?? '')) {
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'cancelled' => 'gray',
                                    'completed' => 'info',
                                    default => 'gray',
                                };
                            })
                            ->icon(function ($state) {
                                return match (strtolower($state ?? '')) {
                                    'pending' => 'heroicon-o-clock',
                                    'approved' => 'heroicon-o-check-circle',
                                    'rejected' => 'heroicon-o-x-circle',
                                    'cancelled' => 'heroicon-o-x-mark',
                                    'completed' => 'heroicon-o-check-badge',
                                    default => null,
                                };
                            }),
                        
                        TextEntry::make('user.name')
                            ->label('Requested By')
                            ->icon('heroicon-o-user'),
                        
                        TextEntry::make('creation_date')
                            ->label('Created At')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-calendar'),
                    ])
                    ->columns(3),
                
                // Sección 2: Detalles del Vehículo
                Section::make('Vehicle Details')
                    ->schema([
                        TextEntry::make('vehicle.plate')
                            ->label('Plate')
                            ->formatStateUsing(fn ($record) => $record && $record->vehicle
                                ? "{$record->vehicle->plate} - {$record->vehicle->brand} {$record->vehicle->model}"
                                : 'N/A'
                            )
                            ->icon('heroicon-o-truck'),
                        
                        TextEntry::make('vehicle.fuelType.name')
                            ->label('Fuel Type')
                            ->badge()
                            ->color('info')
                            ->placeholder('N/A'),
                        
                        TextEntry::make('vehicle.current_mileage')
                            ->label('Current Mileage')
                            ->numeric()
                            ->suffix(' km')
                            ->placeholder('N/A'),
                    ])
                    ->columns(3),
                
                // Sección 3: Detalles del Viaje
                Section::make('Trip Details')
                    ->schema([
                        TextEntry::make('requested_departure_date')
                            ->label('Departure Date & Time')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-arrow-right-circle')
                            ->columnSpan(1),
                        
                        TextEntry::make('requested_return_date')
                            ->label('Return Date & Time')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-arrow-left-circle')
                            ->columnSpan(1),
                        
                        TextEntry::make('destination')
                            ->label('Destination')
                            ->icon('heroicon-o-map-pin')
                            ->placeholder('Not specified')
                            ->columnSpan(1),
                        
                        TextEntry::make('event')
                            ->label('Event / Reason')
                            ->icon('heroicon-o-calendar-days')
                            ->placeholder('Not specified')
                            ->columnSpan(1),
                        
                        TextEntry::make('description')
                            ->label('Description')
                            ->placeholder('No description provided')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                // Sección 4: Información de Aprobación (solo si está aprobada/rechazada)
                Section::make('Approval Information')
                    ->schema([
                        TextEntry::make('approval_date')
                            ->label('Approval Date')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-calendar')
                            ->placeholder('Not approved yet')
                            ->visible(fn ($record) => $record && $record->approval_date !== null),
                        
                        TextEntry::make('approvedBy.name')
                            ->label('Approved By')
                            ->icon('heroicon-o-user-check')
                            ->placeholder('Not approved yet')
                            ->visible(fn ($record) => $record && $record->approvedBy !== null),
                        
                        TextEntry::make('approval_note')
                            ->label(fn ($record) => match($record?->requestStatus?->name) {
                                'Rejected' => 'Rejection Reason',
                                'Cancelled' => 'Cancellation Reason',
                                'Approved' => 'Approval Note',
                                default => 'Note'
                            })
                            ->icon(fn ($record) => match($record?->requestStatus?->name) {
                                'Rejected' => 'heroicon-o-exclamation-circle',
                                'Cancelled' => 'heroicon-o-x-mark',
                                'Approved' => 'heroicon-o-document-text',
                                default => 'heroicon-o-document-text'
                            })
                            ->placeholder('No note provided')
                            ->visible(fn ($record) => $record && $record->approval_note !== null)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record && ($record->approval_date !== null || $record->requestStatus?->name === 'Cancelled'))
                    ->collapsible(),
                
                // Sección 5: Información Adicional
                Section::make('Additional Information')
                    ->schema([
                        TextEntry::make('belongsTo')
                            ->label('Belongs To')
                            ->icon('heroicon-o-building-office')
                            ->placeholder('Not specified'),
                        
                        TextEntry::make('created_at')
                            ->label('Record Created')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-clock')
                            ->columnSpan(1),
                        
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-arrow-path')
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }
}
