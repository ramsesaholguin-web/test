<?php

namespace App\Filament\Resources\HistoryEvidence\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HistoryEvidenceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('history_id')
                    ->relationship('history', 'id')
                    ->required(),
                Select::make('evidence_type_id')
                    ->relationship('evidenceType', 'name')
                    ->required(),
                TextInput::make('url')
                    ->required(),
                TextInput::make('belongsTo')
                    ->required(),
            ]);
    }
}
