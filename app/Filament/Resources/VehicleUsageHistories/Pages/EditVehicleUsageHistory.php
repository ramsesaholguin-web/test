<?php

namespace App\Filament\Resources\VehicleUsageHistories\Pages;

use App\Filament\Resources\VehicleUsageHistories\VehicleUsageHistoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVehicleUsageHistory extends EditRecord
{
    protected static string $resource = VehicleUsageHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
