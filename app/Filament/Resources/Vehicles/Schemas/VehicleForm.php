<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()                    
                    ->schema([
                        Section::make('Basic Information')
                            ->schema([
                                TextInput::make('brand')
                                    ->required(),
                                TextInput::make('model')
                                    ->required(),
                                TextInput::make('year')
                                    ->required()
                                    ->numeric()
                                    ->maxLength(4),
                                    ])
                                    ->columns(2),
                            ]),
                Group::make()                    
                    ->schema([
                        Section::make('Identifiers')
                            ->schema([
                                TextInput::make('vin')
                                    ->required()
                                    ->maxLength(17),
                                TextInput::make('plate')
                                    ->required()
                                    ->maxLength(10),
                            ])
                            ->columns(2),
                    ]),
                Group::make()                    
                    ->schema([
                        Section::make('Details')
                            ->schema([
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
                                MarkdownEditor::make('note')
                                    ->columnSpanFull(),
                                DateTimePicker::make('registration_date')
                                    ->required(),
                                TextInput::make('belongsTo')
                                    ->required(),                                    
                            ])
                            ->collapsible()
                            ->columns(2),
                        ])->columnSpanFull(),    
            ]);
    }
}
