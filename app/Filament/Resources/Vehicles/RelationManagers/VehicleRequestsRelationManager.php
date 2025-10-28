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

class VehicleRequestsRelationManager extends RelationManager
{
    protected static string $relationship = 'vehicleRequests';

    protected static ?string $recordTitleAttribute = 'event';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schema\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Schema\DatePicker::make('requested_departure_date')
                    ->label('Departure Date')
                    ->required(),
                Schema\DatePicker::make('requested_return_date')
                    ->label('Return Date')
                    ->required(),
                Schema\Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->columnSpanFull(),
                Schema\TextInput::make('destination')
                    ->label('Destination'),
                Schema\TextInput::make('event')
                    ->label('Event'),
                Schema\Select::make('request_status_id')
                    ->label('Status')
                    ->relationship('requestStatus', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('event')
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('requested_departure_date')
                    ->label('Departure Date')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('requested_return_date')
                    ->label('Return Date')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('destination')
                    ->label('Destination'),
                TextColumn::make('event')
                    ->label('Event'),
                TextColumn::make('request_status.name')
                    ->label('Status')
                    ->badge(),
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

