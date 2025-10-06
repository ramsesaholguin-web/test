<?php

namespace App\Filament\Resources\VehicleUsageHistories\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VehicleUsageHistoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('request.id')
                    ->numeric(),
                TextEntry::make('departure_mileage')
                    ->numeric(),
                TextEntry::make('departure_fuel_level')
                    ->numeric(),
                TextEntry::make('actual_departure_date')
                    ->dateTime(),
                TextEntry::make('return_mileage')
                    ->numeric(),
                TextEntry::make('return_fuel_level')
                    ->numeric(),
                TextEntry::make('actual_return_date')
                    ->dateTime(),
                TextEntry::make('belongsTo'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
