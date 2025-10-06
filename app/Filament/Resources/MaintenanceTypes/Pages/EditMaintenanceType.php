<?php

namespace App\Filament\Resources\MaintenanceTypes\Pages;

use App\Filament\Resources\MaintenanceTypes\MaintenanceTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMaintenanceType extends EditRecord
{
    protected static string $resource = MaintenanceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
