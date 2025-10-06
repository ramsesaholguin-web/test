<?php

namespace App\Filament\Resources\VehicleUsageHistories\Pages;

use App\Filament\Resources\VehicleUsageHistories\VehicleUsageHistoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVehicleUsageHistory extends ViewRecord
{
    protected static string $resource = VehicleUsageHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
