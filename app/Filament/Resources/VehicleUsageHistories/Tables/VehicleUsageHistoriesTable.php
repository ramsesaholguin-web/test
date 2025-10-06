<?php

namespace App\Filament\Resources\VehicleUsageHistories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehicleUsageHistoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('request.id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('departure_mileage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('departure_fuel_level')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('actual_departure_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('return_mileage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('return_fuel_level')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('actual_return_date')
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
