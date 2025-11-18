<?php

namespace App\Filament\Resources\Maintenances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class MaintenanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Sección 1: Información Básica
                Section::make('Basic Information')
                    ->schema([
                        TextEntry::make('vehicle.plate')
                            ->label('Vehicle')
                            ->formatStateUsing(fn ($record) => $record && $record->vehicle 
                                ? "{$record->vehicle->plate} - {$record->vehicle->brand} {$record->vehicle->model}"
                                : 'N/A'
                            )
                            ->icon('heroicon-o-truck')
                            ->weight('medium')
                            ->copyable(),
                        
                        TextEntry::make('maintenanceType.name')
                            ->label('Maintenance Type')
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-o-wrench-screwdriver')
                            ->placeholder('Not set'),
                        
                        TextEntry::make('maintenance_date')
                            ->label('Maintenance Date')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-calendar'),
                        
                        TextEntry::make('maintenance_mileage')
                            ->label('Maintenance Mileage')
                            ->numeric()
                            ->suffix(' km')
                            ->icon('heroicon-o-chart-bar'),
                    ])
                    ->columns(4),
                
                // Sección 2: Detalles del Mantenimiento
                Section::make('Maintenance Details')
                    ->schema([
                        TextEntry::make('cost')
                            ->label('Cost')
                            ->money('USD')
                            ->icon('heroicon-o-banknotes')
                            ->placeholder('Not specified')
                            ->columnSpan(1),
                        
                        TextEntry::make('workshop')
                            ->label('Workshop')
                            ->icon('heroicon-o-building-office')
                            ->placeholder('Not specified')
                            ->columnSpan(1),
                        
                        TextEntry::make('note')
                            ->label('Note')
                            ->placeholder('No notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                // Sección 3: Próximo Mantenimiento
                Section::make('Next Maintenance')
                    ->schema([
                        TextEntry::make('next_maintenance_date')
                            ->label('Next Maintenance Date')
                            ->state(function ($record) {
                                if (!$record->next_maintenance_date) {
                                    return 'Not scheduled';
                                }
                                return Carbon::parse($record->next_maintenance_date)->format('d/m/Y');
                            })
                            ->badge()
                            ->color(function ($record) {
                                if (!$record->next_maintenance_date) {
                                    return 'gray';
                                }
                                $daysUntil = Carbon::parse($record->next_maintenance_date)->diffInDays(now(), false);
                                if ($daysUntil <= 0) {
                                    return 'danger'; // Vencido
                                }
                                if ($daysUntil <= 7) {
                                    return 'danger'; // En 7 días o menos
                                }
                                if ($daysUntil <= 30) {
                                    return 'warning'; // En 30 días o menos
                                }
                                return 'success';
                            })
                            ->icon(function ($record) {
                                if (!$record->next_maintenance_date) {
                                    return 'heroicon-o-minus-circle';
                                }
                                $daysUntil = Carbon::parse($record->next_maintenance_date)->diffInDays(now(), false);
                                if ($daysUntil <= 30) {
                                    return 'heroicon-o-exclamation-triangle';
                                }
                                return 'heroicon-o-calendar-days';
                            })
                            ->placeholder('Not scheduled')
                            ->columnSpan(1),
                        
                        TextEntry::make('next_maintenance_mileage')
                            ->label('Next Maintenance Mileage')
                            ->numeric()
                            ->suffix(' km')
                            ->icon('heroicon-o-chart-bar')
                            ->placeholder('Not specified')
                            ->columnSpan(1),
                        
                        TextEntry::make('days_until_next')
                            ->label('Days Until Next Maintenance')
                            ->state(function ($record) {
                                if (!$record->next_maintenance_date) {
                                    return 'N/A';
                                }
                                $daysUntil = Carbon::parse($record->next_maintenance_date)->diffInDays(now(), false);
                                if ($daysUntil < 0) {
                                    return abs($daysUntil) . ' days overdue';
                                }
                                return $daysUntil . ' days';
                            })
                            ->badge()
                            ->color(function ($record) {
                                if (!$record->next_maintenance_date) {
                                    return null;
                                }
                                $daysUntil = Carbon::parse($record->next_maintenance_date)->diffInDays(now(), false);
                                if ($daysUntil <= 0) {
                                    return 'danger';
                                }
                                if ($daysUntil <= 7) {
                                    return 'danger';
                                }
                                if ($daysUntil <= 30) {
                                    return 'warning';
                                }
                                return 'success';
                            })
                            ->icon('heroicon-o-clock')
                            ->placeholder('N/A')
                            ->columnSpan(1),
                    ])
                    ->columns(3),
                
                // Sección 4: Información Adicional
                Section::make('Additional Information')
                    ->schema([
                        TextEntry::make('belongsTo')
                            ->label('Belongs To')
                            ->icon('heroicon-o-building-office')
                            ->placeholder('Not specified')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                // Sección 5: Fechas del Sistema
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
