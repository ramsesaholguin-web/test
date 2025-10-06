<?php

namespace App\Filament\Resources\EvidenceTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EvidenceTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
