<?php

namespace App\Filament\Resources\VehicleDocuments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class VehicleDocumentInfolist
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
                        
                        TextEntry::make('document_name')
                            ->label('Document Name')
                            ->icon('heroicon-o-document')
                            ->weight('medium'),
                        
                        TextEntry::make('file_path')
                            ->label('File Path')
                            ->icon('heroicon-o-paper-clip')
                            ->copyable()
                            ->url(fn ($record) => $record->file_path ? asset('storage/' . $record->file_path) : null, shouldOpenInNewTab: true)
                            ->placeholder('No file'),
                        
                        TextEntry::make('upload_date')
                            ->label('Upload Date')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-calendar'),
                    ])
                    ->columns(2),
                
                // Sección 2: Información de Expiración
                Section::make('Expiration Information')
                    ->schema([
                        TextEntry::make('expiration_date')
                            ->label('Expiration Date')
                            ->date('d/m/Y')
                            ->badge()
                            ->color(function ($record) {
                                if (!$record->expiration_date) {
                                    return 'gray';
                                }
                                $daysUntil = Carbon::parse($record->expiration_date)->diffInDays(now(), false);
                                if ($daysUntil < 0) {
                                    return 'danger'; // Vencido
                                }
                                if ($daysUntil <= 30) {
                                    return 'danger'; // En 30 días o menos
                                }
                                if ($daysUntil <= 60) {
                                    return 'warning'; // En 60 días o menos
                                }
                                return 'success';
                            })
                            ->icon(function ($record) {
                                if (!$record->expiration_date) {
                                    return 'heroicon-o-minus-circle';
                                }
                                $daysUntil = Carbon::parse($record->expiration_date)->diffInDays(now(), false);
                                if ($daysUntil <= 60) {
                                    return 'heroicon-o-exclamation-triangle';
                                }
                                return 'heroicon-o-calendar-days';
                            })
                            ->placeholder('No expiration date')
                            ->columnSpan(1),
                        
                        TextEntry::make('expiration_status')
                            ->label('Expiration Status')
                            ->state(function ($record) {
                                if (!$record->expiration_date) {
                                    return 'No expiration date';
                                }
                                $daysUntil = Carbon::parse($record->expiration_date)->diffInDays(now(), false);
                                if ($daysUntil < 0) {
                                    return abs($daysUntil) . ' days overdue';
                                }
                                if ($daysUntil == 0) {
                                    return 'Expires today';
                                }
                                if ($daysUntil <= 7) {
                                    return 'Expires in ' . $daysUntil . ' day(s) - URGENT';
                                }
                                if ($daysUntil <= 30) {
                                    return 'Expires in ' . $daysUntil . ' days - Soon';
                                }
                                if ($daysUntil <= 60) {
                                    return 'Expires in ' . $daysUntil . ' days';
                                }
                                return 'Valid - Expires in ' . $daysUntil . ' days';
                            })
                            ->badge()
                            ->color(function ($record) {
                                if (!$record->expiration_date) {
                                    return 'gray';
                                }
                                $daysUntil = Carbon::parse($record->expiration_date)->diffInDays(now(), false);
                                if ($daysUntil < 0) {
                                    return 'danger';
                                }
                                if ($daysUntil <= 30) {
                                    return 'danger';
                                }
                                if ($daysUntil <= 60) {
                                    return 'warning';
                                }
                                return 'success';
                            })
                            ->icon(function ($record) {
                                if (!$record->expiration_date) {
                                    return 'heroicon-o-minus-circle';
                                }
                                $daysUntil = Carbon::parse($record->expiration_date)->diffInDays(now(), false);
                                if ($daysUntil <= 60) {
                                    return 'heroicon-o-exclamation-triangle';
                                }
                                return 'heroicon-o-check-circle';
                            })
                            ->placeholder('No expiration date')
                            ->columnSpan(1),
                        
                        TextEntry::make('days_until_expiration')
                            ->label('Days Until Expiration')
                            ->state(function ($record) {
                                if (!$record->expiration_date) {
                                    return 'N/A';
                                }
                                $daysUntil = Carbon::parse($record->expiration_date)->diffInDays(now(), false);
                                if ($daysUntil < 0) {
                                    return abs($daysUntil) . ' days overdue';
                                }
                                return $daysUntil . ' days';
                            })
                            ->badge()
                            ->color(function ($record) {
                                if (!$record->expiration_date) {
                                    return null;
                                }
                                $daysUntil = Carbon::parse($record->expiration_date)->diffInDays(now(), false);
                                if ($daysUntil <= 0) {
                                    return 'danger';
                                }
                                if ($daysUntil <= 30) {
                                    return 'danger';
                                }
                                if ($daysUntil <= 60) {
                                    return 'warning';
                                }
                                return 'success';
                            })
                            ->icon('heroicon-o-clock')
                            ->placeholder('N/A')
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->visible(fn ($record) => $record->expiration_date !== null),
                
                // Sección 3: Información de Subida
                Section::make('Upload Information')
                    ->schema([
                        TextEntry::make('uploadedBy.name')
                            ->label('Uploaded By')
                            ->icon('heroicon-o-user')
                            ->placeholder('Unknown')
                            ->columnSpan(1),
                        
                        TextEntry::make('upload_date')
                            ->label('Upload Date')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-calendar')
                            ->since()
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                
                // Sección 4: Acceso al Archivo
                Section::make('File Access')
                    ->schema([
                        TextEntry::make('file_path')
                            ->label('File Path')
                            ->copyable()
                            ->url(fn ($record) => $record->file_path ? asset('storage/' . $record->file_path) : null, shouldOpenInNewTab: true)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-link')
                            ->placeholder('No file available')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
                
                // Sección 5: Información Adicional
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
                
                // Sección 6: Fechas del Sistema
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
