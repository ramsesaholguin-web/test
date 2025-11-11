<?php

namespace App\Filament\Resources\Maintenances\Pages;

use App\Filament\Resources\Maintenances\MaintenanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMaintenance extends CreateRecord
{
    protected static string $resource = MaintenanceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set belongsTo if not provided
        if (!isset($data['belongsTo']) || empty($data['belongsTo'])) {
            $data['belongsTo'] = auth()->user()->name ?? 'System';
        }

        return $data;
    }
}
