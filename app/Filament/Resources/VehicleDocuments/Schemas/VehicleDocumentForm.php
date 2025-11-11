<?php

namespace App\Filament\Resources\VehicleDocuments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;
use App\Models\Vehicle;

class VehicleDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Hidden fields for automatic assignment
                Hidden::make('uploaded_by')
                    ->default(fn () => auth()->id()),
                Hidden::make('upload_date')
                    ->default(fn () => now()),
                
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('Document details', [
                        Select::make('vehicle_id')
                            ->label('Vehicle')
                            ->options(function () {
                                return Vehicle::orderBy('plate')
                                    ->get()
                                    ->mapWithKeys(function (Vehicle $vehicle) {
                                        $label = "{$vehicle->plate} - {$vehicle->brand} {$vehicle->model}";
                                        if ($vehicle->year) {
                                            $label .= " ({$vehicle->year})";
                                        }
                                        return [$vehicle->id => $label];
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->required(),
                        TextInput::make('document_name')
                            ->label('Document Name')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('file_path')
                            ->label('File')
                            ->directory('vehicle-documents')
                            ->downloadable()
                            ->openable()
                            ->acceptedFileTypes(['pdf', 'jpg', 'jpeg', 'png'])
                            ->required(),
                    ])->columns(2),
                ])->columnSpanFull(),
                FormTemplate::basicSection('Dates & ownership', [
                    DatePicker::make('expiration_date')
                        ->label('Expiration Date'),
                    TextInput::make('belongsTo')
                        ->label('Owner')
                        ->default(fn () => auth()->user()->name ?? 'System')
                        ->required(),
                ])->columns(2),
            ]);
    }
}
