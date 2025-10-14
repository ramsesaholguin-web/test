<?php

namespace App\Filament\Resources\VehicleDocuments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;

class VehicleDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('Document details', [
                        Select::make('vehicle_id')
                            ->relationship('vehicle', 'id')
                            ->required(),
                        FormTemplate::labeledText('document_name', 'Document name', true),
                        FormTemplate::labeledText('file_path', 'File path', true),
                    ])->columns(2),
                ])->columnSpanFull(),
                FormTemplate::basicSection('Dates & ownership', [
                    DatePicker::make('expiration_date'),
                    DateTimePicker::make('upload_date')
                        ->required(),
                    FormTemplate::labeledText('uploaded_by', 'Uploaded by', true)
                        ->numeric(),
                    FormTemplate::labeledText('belongsTo', 'Owner', true),
                ])->columns(2),
            ]);
    }
}
