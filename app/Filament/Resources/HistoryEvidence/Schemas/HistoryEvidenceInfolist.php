<?php

namespace App\Filament\Resources\HistoryEvidence\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class HistoryEvidenceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('history.id')
                    ->numeric(),
                TextEntry::make('evidenceType.name')
                    ->numeric(),
                TextEntry::make('url'),
                TextEntry::make('belongsTo'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
