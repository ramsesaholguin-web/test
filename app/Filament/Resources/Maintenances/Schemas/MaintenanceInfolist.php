<?php

namespace App\Filament\Resources\Maintenances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MaintenanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('vehicle.id')
                    ->numeric(),
                TextEntry::make('maintenanceType.name')
                    ->numeric(),
                TextEntry::make('maintenance_date')
                    ->dateTime(),
                TextEntry::make('maintenance_mileage')
                    ->numeric(),
                TextEntry::make('cost')
                    ->money(),
                TextEntry::make('workshop'),
                TextEntry::make('next_maintenance_mileage')
                    ->numeric(),
                TextEntry::make('next_maintenance_date')
                    ->dateTime(),
                TextEntry::make('belongsTo'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
