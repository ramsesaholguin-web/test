<?php

namespace App\Filament\Resources\AccountStatuses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;

class AccountStatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('Account status', [
                        TextInput::make('name')
                            ->required(),
                    ])->columns(2),
                ]),
            ]);
    }
}
