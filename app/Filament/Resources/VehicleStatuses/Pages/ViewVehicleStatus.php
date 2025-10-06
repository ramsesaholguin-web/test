<?php

namespace App\Filament\Resources\VehicleStatuses\Pages;

use App\Filament\Resources\VehicleStatuses\VehicleStatusResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVehicleStatus extends ViewRecord
{
    protected static string $resource = VehicleStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
