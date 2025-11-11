<?php

namespace App\Filament\Resources\Vehicles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('brand')
                    ->searchable(),
                TextColumn::make('model')
                    ->searchable(),
                TextColumn::make('year')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('plate')
                    ->searchable(),
                TextColumn::make('vin')
                    ->searchable(),
                TextColumn::make('fuelType.name')
                    ->label('Tipo de Combustible')
                    ->badge()
                    ->sortable(),
                TextColumn::make('current_mileage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('maintenances_count')
                    ->label('Mantenimientos')
                    ->counts('maintenances')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('last_maintenance_date')
                    ->label('Último Mantenimiento')
                    ->state(function ($record) {
                        $lastMaintenance = $record->maintenances()->latest('maintenance_date')->first();
                        if (!$lastMaintenance || !$lastMaintenance->maintenance_date) {
                            return 'N/A';
                        }
                        
                        // Con el cast 'datetime', maintenance_date ya es una instancia de Carbon
                        return $lastMaintenance->maintenance_date->format('d/m/Y');
                    })
                    ->sortable(query: function ($query, string $direction) {
                        return $query->orderByRaw("(
                            SELECT MAX(maintenance_date) 
                            FROM maintenances 
                            WHERE vehicle_id = vehicles.id
                        ) {$direction}");
                    })
                    ->toggleable(),
                TextColumn::make('status.name')
                    ->label('Estado')
                    ->badge()
                    ->sortable(),
                TextColumn::make('current_location')
                    ->searchable(),
                TextColumn::make('registration_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('belongsTo')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
