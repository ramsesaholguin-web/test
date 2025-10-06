<?php

namespace App\Filament\Resources\Warnings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WarningsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('warning_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('warningType.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('evidence_url')
                    ->searchable(),
                TextColumn::make('warned_by')
                    ->numeric()
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
