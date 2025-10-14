<?php

namespace App\Filament\Resources\EvidenceTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;

class EvidenceTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('Evidence type', [
                        TextInput::make('name')
                            ->required(),
                    ])->columns(2),
                ]),
            ]);
    }
}
