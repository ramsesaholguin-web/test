<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('User information', [
                        FormTemplate::labeledText('name', 'Full name', true),
                        FormTemplate::labeledText('email', 'Email address', true)
                            ->email(),
                    ])->columns(2),
                ]),
                DateTimePicker::make('email_verified_at'),
                FormTemplate::labeledText('password', 'Password', true)
                    ->password(),
            ]);
    }
}
