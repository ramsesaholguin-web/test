<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('brand')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                TextInput::make('year')
                    ->required()
                    ->numeric(),
                TextInput::make('plate')
                    ->required(),
                TextInput::make('vin')
                    ->required(),
                Select::make('fuel_type_id')
                    ->relationship('fuelType', 'name')
                    ->required(),
                TextInput::make('current_mileage')
                    ->required()
                    ->numeric(),
                Select::make('status_id')
                    ->relationship('status', 'name')
                    ->required(),
                TextInput::make('current_location'),
                Textarea::make('note')
                    ->columnSpanFull(),
                DateTimePicker::make('registration_date')
                    ->required(),
                TextInput::make('belongsTo')
                    ->required(),
            ]);
    }
}
