<?php

namespace App\Filament\Resources\Vehicles\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class MaintenancesRelationManager extends RelationManager
{
    protected static string $relationship = 'maintenances';

    protected static ?string $recordTitleAttribute = 'maintenance_date';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schema\Select::make('maintenance_type_id')
                    ->label('Maintenance Type')
                    ->relationship('maintenanceType', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Schema\DatePicker::make('maintenance_date')
                    ->label('Maintenance Date')
                    ->required(),
                Schema\TextInput::make('maintenance_mileage')
                    ->label('Mileage')
                    ->numeric()
                    ->suffix('km'),
                Schema\TextInput::make('cost')
                    ->label('Cost')
                    ->numeric()
                    ->prefix('$'),
                Schema\TextInput::make('workshop')
                    ->label('Workshop'),
                Schema\TextInput::make('next_maintenance_mileage')
                    ->label('Next Maintenance Mileage')
                    ->numeric()
                    ->suffix('km'),
                Schema\DatePicker::make('next_maintenance_date')
                    ->label('Next Maintenance Date'),
                Schema\Textarea::make('note')
                    ->label('Note')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('maintenance_date')
            ->columns([
                TextColumn::make('maintenance_type.name')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('maintenance_date')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('maintenance_mileage')
                    ->label('Mileage')
                    ->suffix(' km')
                    ->sortable(),
                TextColumn::make('cost')
                    ->label('Cost')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('workshop')
                    ->label('Workshop'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

