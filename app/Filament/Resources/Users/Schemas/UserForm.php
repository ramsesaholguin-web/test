<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
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
                    FormTemplate::basicSection('User Information', [
                        FormTemplate::labeledText('name', 'Full Name', true),
                        FormTemplate::labeledText('email', 'Email Address', true)
                            ->email()
                            ->unique(ignoreRecord: true),
                    ])->columns(2),
                ]),
                
                FormTemplate::basicSection('Account Settings', [
                    Select::make('account_status_id')
                        ->label('Account Status')
                        ->relationship('accountStatus', 'name')
                        ->searchable()
                        ->preload()
                        ->placeholder('Select account status...')
                        ->helperText('The status of the user account'),
                    
                    Select::make('user_status_id')
                        ->label('User Status')
                        ->relationship('userStatus', 'name')
                        ->searchable()
                        ->preload()
                        ->placeholder('Select user status...')
                        ->helperText('The current status of the user'),
                    
                    DateTimePicker::make('email_verified_at')
                        ->label('Email Verified At')
                        ->displayFormat('d/m/Y H:i')
                        ->native(false)
                        ->seconds(false)
                        ->helperText('Date when the email was verified'),
                ])->columns(3),
                
                FormTemplate::basicSection('Password', [
                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->required(fn ($livewire) => $livewire instanceof \App\Filament\Resources\Users\Pages\CreateUser)
                        ->dehydrated(fn ($state) => filled($state))
                        ->dehydrateStateUsing(fn ($state) => \Hash::make($state))
                        ->helperText(fn ($livewire) => $livewire instanceof \App\Filament\Resources\Users\Pages\CreateUser 
                            ? 'Enter a secure password for the new user'
                            : 'Leave blank to keep current password. Enter new password to change it.'
                        )
                        ->minLength(8)
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),
            ]);
    }
}
