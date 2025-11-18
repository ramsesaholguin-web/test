<?php

namespace App\Filament\Resources\Warnings\Pages;

use App\Filament\Resources\Warnings\WarningResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWarning extends CreateRecord
{
    protected static string $resource = WarningResource::class;

    /**
     * Mutate form data before creating
     * Assign warned_by automatically to authenticated user
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Asignar automáticamente el usuario autenticado como quien crea la advertencia
        if (empty($data['warned_by'])) {
            $data['warned_by'] = auth()->id();
        }

        // Asignar belongsTo por defecto si no se proporciona
        if (empty($data['belongsTo'])) {
            $data['belongsTo'] = 'Admin';
        }

        return $data;
    }
}
