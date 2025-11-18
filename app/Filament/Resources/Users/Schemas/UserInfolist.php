<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Sección 1: Información Personal
                Section::make('Personal Information')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Full Name')
                            ->icon('heroicon-o-user')
                            ->weight('medium'),
                        
                        TextEntry::make('email')
                            ->label('Email Address')
                            ->icon('heroicon-o-envelope')
                            ->copyable()
                            ->url(fn ($state) => "mailto:{$state}"),
                        
                        TextEntry::make('email_verified_at')
                            ->label('Email Verified')
                            ->dateTime('d/m/Y H:i')
                            ->icon(fn ($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                            ->color(fn ($state) => $state ? 'success' : 'gray')
                            ->placeholder('Not verified'),
                    ])
                    ->columns(3),
                
                // Sección 2: Estados y Estado de la Cuenta
                Section::make('Account Status')
                    ->schema([
                        TextEntry::make('accountStatus.name')
                            ->label('Account Status')
                            ->badge()
                            ->color(fn (string $state): string => match (strtolower($state ?? '')) {
                                'active' => 'success',
                                'inactive' => 'gray',
                                'suspended' => 'danger',
                                'pending' => 'warning',
                                default => 'gray',
                            })
                            ->icon(fn ($state) => match (strtolower($state ?? '')) {
                                'active' => 'heroicon-o-check-circle',
                                'inactive' => 'heroicon-o-pause-circle',
                                'suspended' => 'heroicon-o-x-circle',
                                'pending' => 'heroicon-o-clock',
                                default => null,
                            })
                            ->placeholder('Not set'),
                        
                        TextEntry::make('userStatus.name')
                            ->label('User Status')
                            ->badge()
                            ->color(fn (string $state): string => match (strtolower($state ?? '')) {
                                'active' => 'success',
                                'inactive' => 'gray',
                                'on leave' => 'warning',
                                'terminated' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn ($state) => match (strtolower($state ?? '')) {
                                'active' => 'heroicon-o-user-circle',
                                'inactive' => 'heroicon-o-user-minus',
                                'on leave' => 'heroicon-o-calendar',
                                'terminated' => 'heroicon-o-user-x',
                                default => null,
                            })
                            ->placeholder('Not set'),
                    ])
                    ->columns(2),
                
                // Sección 3: Estadísticas
                Section::make('Statistics')
                    ->schema([
                        TextEntry::make('vehicle_requests_count')
                            ->label('Vehicle Requests')
                            ->numeric()
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-o-document-check')
                            ->default(0)
                            ->formatStateUsing(fn ($state, $record) => $record->vehicleRequests()->count()),
                        
                        TextEntry::make('warnings_count')
                            ->label('Warnings')
                            ->numeric()
                            ->badge()
                            ->color(fn ($state, $record) => $record->warnings()->count() > 0 ? 'danger' : 'gray')
                            ->icon('heroicon-o-exclamation-triangle')
                            ->default(0)
                            ->formatStateUsing(fn ($state, $record) => $record->warnings()->count()),
                        
                        TextEntry::make('vehicle_documents_count')
                            ->label('Documents Uploaded')
                            ->numeric()
                            ->badge()
                            ->color('success')
                            ->icon('heroicon-o-document')
                            ->default(0)
                            ->formatStateUsing(fn ($state, $record) => $record->vehicleDocuments()->count()),
                    ])
                    ->columns(3),
                
                // Sección 4: Información Adicional
                Section::make('Additional Information')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Account Created')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-calendar')
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
