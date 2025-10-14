<?php

namespace App\Filament\Resources\MaintenanceTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;

class MaintenanceTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('Maintenance type', [
                        TextInput::make('name')
                            ->required(),
                    ])->columns(2),
                ]),
            ]);
    }
}
