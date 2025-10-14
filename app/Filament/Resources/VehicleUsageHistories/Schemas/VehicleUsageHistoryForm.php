<?php

namespace App\Filament\Resources\VehicleUsageHistories\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;

class VehicleUsageHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('Usage record', [
                        Select::make('request_id')
                            ->relationship('request', 'id')
                            ->required(),
                        TextInput::make('departure_mileage')
                            ->required()
                            ->numeric(),
                        TextInput::make('departure_fuel_level')
                            ->required()
                            ->numeric(),
                        DateTimePicker::make('actual_departure_date')
                            ->required(),
                        Textarea::make('departure_note')
                            ->columnSpanFull(),
                        TextInput::make('return_mileage')
                            ->numeric(),
                        TextInput::make('return_fuel_level')
                            ->numeric(),
                        DateTimePicker::make('actual_return_date'),
                        Textarea::make('return_note')
                            ->columnSpanFull(),
                        FormTemplate::labeledText('belongsTo', 'Owner', true),
                    ])->columns(2),
                ]),
            ]);
    }
}
