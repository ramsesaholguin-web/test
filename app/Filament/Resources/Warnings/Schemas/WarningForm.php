<?php

namespace App\Filament\Resources\Warnings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class WarningForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'id')
                    ->required(),
                DateTimePicker::make('warning_date')
                    ->required(),
                Select::make('warning_type_id')
                    ->relationship('warningType', 'name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('evidence_url'),
                TextInput::make('warned_by')
                    ->required()
                    ->numeric(),
                TextInput::make('belongsTo')
                    ->required(),
            ]);
    }
}
