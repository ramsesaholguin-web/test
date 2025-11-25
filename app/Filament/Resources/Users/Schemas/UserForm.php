<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;
use Spatie\Permission\Models\Role;

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
                    
                    Select::make('role')
                        ->label('Role')
                        ->options(function () {
                            return Role::where('guard_name', 'web')
                                ->whereIn('name', ['super_admin', 'usuario'])
                                ->pluck('name', 'name')
                                ->mapWithKeys(function ($name) {
                                    $labels = [
                                        'super_admin' => 'Super Admin',
                                        'usuario' => 'Usuario',
                                    ];
                                    return [$name => $labels[$name] ?? ucfirst($name)];
                                });
                        })
                        ->default('usuario')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->placeholder('Select user role...')
                        ->helperText('The role determines the user\'s permissions in the system')
                        ->dehydrated(true)
                        ->afterStateHydrated(function (Select $component, $state, $record) {
                            if ($record && $record->exists) {
                                $role = $record->roles->first();
                                $component->state($role ? $role->name : 'usuario');
                            }
                        }),
                    
                    DateTimePicker::make('email_verified_at')
                        ->label('Email Verified At')
                        ->displayFormat('d/m/Y H:i')
                        ->native(false)
                        ->seconds(false)
                        ->helperText('Date when the email was verified'),
                ])->columns(2),
                
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
