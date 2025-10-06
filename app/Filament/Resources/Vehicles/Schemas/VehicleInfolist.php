<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VehicleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('brand'),
                TextEntry::make('model'),
                TextEntry::make('year')
                    ->numeric(),
                TextEntry::make('plate'),
                TextEntry::make('vin'),
                TextEntry::make('fuelType.name')
                    ->numeric(),
                TextEntry::make('current_mileage')
                    ->numeric(),
                TextEntry::make('status.name')
                    ->numeric(),
                TextEntry::make('current_location'),
                TextEntry::make('registration_date')
                    ->dateTime(),
                TextEntry::make('belongsTo'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
