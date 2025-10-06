<?php

namespace App\Filament\Resources\VehicleRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VehicleRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.id')
                    ->numeric(),
                TextEntry::make('vehicle.id')
                    ->numeric(),
                TextEntry::make('requested_departure_date')
                    ->dateTime(),
                TextEntry::make('requested_return_date')
                    ->dateTime(),
                TextEntry::make('destination'),
                TextEntry::make('event'),
                TextEntry::make('requestStatus.name')
                    ->numeric(),
                TextEntry::make('approval_date')
                    ->dateTime(),
                TextEntry::make('approved_by')
                    ->numeric(),
                TextEntry::make('creation_date')
                    ->dateTime(),
                TextEntry::make('belongsTo'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
