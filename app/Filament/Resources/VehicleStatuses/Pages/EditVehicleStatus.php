<?php

namespace App\Filament\Resources\VehicleStatuses\Pages;

use App\Filament\Resources\VehicleStatuses\VehicleStatusResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVehicleStatus extends EditRecord
{
    protected static string $resource = VehicleStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
