<?php

namespace App\Filament\Resources\VehicleUsageHistories\Pages;

use App\Filament\Resources\VehicleUsageHistories\VehicleUsageHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicleUsageHistories extends ListRecords
{
    protected static string $resource = VehicleUsageHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
