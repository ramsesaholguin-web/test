<?php

namespace App\Filament\Resources\Maintenances\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MaintenanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'id')
                    ->required(),
                Select::make('maintenance_type_id')
                    ->relationship('maintenanceType', 'name')
                    ->required(),
                DateTimePicker::make('maintenance_date')
                    ->required(),
                TextInput::make('maintenance_mileage')
                    ->required()
                    ->numeric(),
                TextInput::make('cost')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('workshop'),
                Textarea::make('note')
                    ->columnSpanFull(),
                TextInput::make('next_maintenance_mileage')
                    ->numeric(),
                DateTimePicker::make('next_maintenance_date'),
                TextInput::make('belongsTo')
                    ->required(),
            ]);
    }
}
