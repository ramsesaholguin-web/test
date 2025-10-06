<?php

namespace App\Filament\Resources\VehicleDocuments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VehicleDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'id')
                    ->required(),
                TextInput::make('document_name')
                    ->required(),
                TextInput::make('file_path')
                    ->required(),
                DatePicker::make('expiration_date'),
                DateTimePicker::make('upload_date')
                    ->required(),
                TextInput::make('uploaded_by')
                    ->required()
                    ->numeric(),
                TextInput::make('belongsTo')
                    ->required(),
            ]);
    }
}
