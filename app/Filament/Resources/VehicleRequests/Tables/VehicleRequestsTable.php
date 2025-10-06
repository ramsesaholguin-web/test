<?php

namespace App\Filament\Resources\VehicleRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehicleRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('vehicle.id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('requested_departure_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('requested_return_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('destination')
                    ->searchable(),
                TextColumn::make('event')
                    ->searchable(),
                TextColumn::make('requestStatus.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approval_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('approved_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('creation_date')
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
