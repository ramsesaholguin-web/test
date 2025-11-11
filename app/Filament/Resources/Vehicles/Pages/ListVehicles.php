<?php

namespace App\Filament\Resources\Vehicles\Pages;

use App\Filament\Resources\Vehicles\VehicleResource;
use App\Filament\Resources\Vehicles\Widgets\MantenimientosProximosVehiculos;
use App\Filament\Resources\Vehicles\Widgets\MantenimientosRecientesVehiculos;
use App\Filament\Resources\Vehicles\Widgets\VehiculosStats;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicles extends ListRecords
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            VehiculosStats::class,
            MantenimientosRecientesVehiculos::class,
            MantenimientosProximosVehiculos::class,
        ];
    }
}
