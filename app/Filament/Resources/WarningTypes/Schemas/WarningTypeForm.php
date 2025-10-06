<?php

namespace App\Filament\Resources\WarningTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WarningTypeForm
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
