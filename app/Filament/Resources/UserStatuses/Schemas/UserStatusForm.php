<?php

namespace App\Filament\Resources\UserStatuses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;

class UserStatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('User status', [
                        TextInput::make('name')
                            ->required(),
                    ])->columns(2),
                ]),
            ]);
    }
}
