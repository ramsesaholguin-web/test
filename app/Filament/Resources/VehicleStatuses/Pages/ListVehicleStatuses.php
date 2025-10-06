<?php

namespace App\Filament\Resources\VehicleStatuses\Pages;

use App\Filament\Resources\VehicleStatuses\VehicleStatusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicleStatuses extends ListRecords
{
    protected static string $resource = VehicleStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
