<?php

namespace App\Filament\Resources\Warnings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WarningInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.id')
                    ->numeric(),
                TextEntry::make('warning_date')
                    ->dateTime(),
                TextEntry::make('warningType.name')
                    ->numeric(),
                TextEntry::make('evidence_url'),
                TextEntry::make('warned_by')
                    ->numeric(),
                TextEntry::make('belongsTo'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
