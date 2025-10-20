<?php

namespace App\Filament\Resources\VehicleRequests\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;

class VehicleRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('Request details', [
                        Select::make('user_id')
                            ->relationship('user', 'id')
                            ->required(),
                        Select::make('vehicle_id')
                            ->relationship('vehicle', 'id')
                            ->required(),
                        DateTimePicker::make('requested_departure_date')
                            ->required(),
                        DateTimePicker::make('requested_return_date')
                            ->required(),
                    ])->columns(2),
                ]),                              
                FormTemplate::basicSection('Approval', [
                    Select::make('request_status_id')
                        ->relationship('requestStatus', 'name')
                        ->required(),
                    DateTimePicker::make('approval_date'),
                    TextInput::make('approved_by')
                        ->numeric(),
                    Textarea::make('approval_note')
                        ->columnSpanFull(),
                ])->columns(2),
                FormTemplate::basicSection('Trip', [
                    TextInput::make('destination'),
                    TextInput::make('event'),
                ])->columns(2),
                Textarea::make('description'),   
                DateTimePicker::make('creation_date')
                    ->required(),
                FormTemplate::labeledText('belongsTo', 'Owner', true),
            ]);
    }
}
