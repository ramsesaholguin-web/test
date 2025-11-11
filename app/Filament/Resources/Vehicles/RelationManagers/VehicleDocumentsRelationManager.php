<?php

namespace App\Filament\Resources\Vehicles\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class VehicleDocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'vehicleDocuments';

    protected static ?string $recordTitleAttribute = 'document_name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schema\TextInput::make('document_name')
                    ->label('Document Name')
                    ->required()
                    ->maxLength(255),
                Schema\FileUpload::make('file_path')
                    ->label('File')
                    ->directory('vehicle-documents')
                    ->downloadable()
                    ->openable()
                    ->acceptedFileTypes(['pdf', 'jpg', 'jpeg', 'png']),
                Schema\DatePicker::make('expiration_date')
                    ->label('Expiration Date'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('document_name')
            ->columns([
                TextColumn::make('document_name')
                    ->label('Document Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('expiration_date')
                    ->label('Expiration Date')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('upload_date')
                    ->label('Upload Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Create Document')
                    ->form([
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
                        DatePicker::make('expiration_date')
                            ->label('Expiration Date'),
                    ])
                    ->using(function (array $data): \App\Models\VehicleDocument {
                        $data['vehicle_id'] = $this->ownerRecord->id;
                        $data['upload_date'] = now();
                        // Set uploaded_by to current user
                        if (!isset($data['uploaded_by']) || empty($data['uploaded_by'])) {
                            $data['uploaded_by'] = auth()->id();
                        }
                        // Set belongsTo if not provided
                        if (!isset($data['belongsTo']) || empty($data['belongsTo'])) {
                            $data['belongsTo'] = auth()->user()->name ?? 'System';
                        }
                        return \App\Models\VehicleDocument::create($data);
                    }),
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

