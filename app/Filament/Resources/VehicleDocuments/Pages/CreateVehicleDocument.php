<?php

namespace App\Filament\Resources\VehicleDocuments\Pages;

use App\Filament\Resources\VehicleDocuments\VehicleDocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVehicleDocument extends CreateRecord
{
    protected static string $resource = VehicleDocumentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set uploaded_by to current user
        if (!isset($data['uploaded_by']) || empty($data['uploaded_by'])) {
            $data['uploaded_by'] = auth()->id();
        }

        // Set upload_date to now
        if (!isset($data['upload_date'])) {
            $data['upload_date'] = now();
        }

        // Set belongsTo if not provided
        if (!isset($data['belongsTo']) || empty($data['belongsTo'])) {
            $data['belongsTo'] = auth()->user()->name ?? 'System';
        }

        return $data;
    }
}
