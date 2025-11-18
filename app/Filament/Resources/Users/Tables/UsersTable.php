<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),
                
                TextColumn::make('accountStatus.name')
                    ->label('Account Status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state ?? '')) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'suspended' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->sortable()
                    ->placeholder('Not set'),
                
                TextColumn::make('userStatus.name')
                    ->label('User Status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state ?? '')) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'on leave' => 'warning',
                        'terminated' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->placeholder('Not set'),
                
                TextColumn::make('vehicle_requests_count')
                    ->label('Requests')
                    ->counts('vehicleRequests')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('warnings_count')
                    ->label('Warnings')
                    ->counts('warnings')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'gray')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->icon(fn ($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->toggleable(),
                
                TextColumn::make('updated_at')
                    ->label('Last Activity')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtro por Estado de Cuenta
                SelectFilter::make('account_status_id')
                    ->label('Account Status')
                    ->relationship('accountStatus', 'name')
                    ->preload()
                    ->searchable(),
                
                // Filtro por Estado de Usuario
                SelectFilter::make('user_status_id')
                    ->label('User Status')
                    ->relationship('userStatus', 'name')
                    ->preload()
                    ->searchable(),
                
                // Filtro por Email Verificado
                TernaryFilter::make('email_verified_at')
                    ->label('Email Verified')
                    ->placeholder('All users')
                    ->trueLabel('Verified only')
                    ->falseLabel('Not verified only')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('email_verified_at'),
                        false: fn ($query) => $query->whereNull('email_verified_at'),
                        blank: fn ($query) => $query,
                    ),
                
                // Filtro por usuarios con advertencias
                TernaryFilter::make('has_warnings')
                    ->label('Has Warnings')
                    ->placeholder('All users')
                    ->trueLabel('With warnings')
                    ->falseLabel('Without warnings')
                    ->queries(
                        true: fn ($query) => $query->has('warnings'),
                        false: fn ($query) => $query->doesntHave('warnings'),
                        blank: fn ($query) => $query,
                    ),
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
