<?php

namespace App\Filament\Resources\VehicleStatuses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;

class VehicleStatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('Vehicle status', [
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('color'),
                    ])->columns(2),
                ]),
            ]);
    }
}
