<?php

namespace App\Filament\Resources\WarningTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;

class WarningTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('Warning type', [
                        TextInput::make('name')
                            ->required(),
                    ])->columns(2),
                ]),
            ]);
    }
}
