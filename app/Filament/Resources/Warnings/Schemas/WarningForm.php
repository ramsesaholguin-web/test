<?php

namespace App\Filament\Resources\Warnings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;

class WarningForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('Warning details', [
                        Select::make('user_id')
                            ->relationship('user', 'id')
                            ->required(),
                        DateTimePicker::make('warning_date')
                            ->required(),
                        Select::make('warning_type_id')
                            ->relationship('warningType', 'name')
                            ->required(),
                    ])->columns(2),
                ]),
                FormTemplate::basicSection('Additional', [
                    FormTemplate::labeledText('evidence_url', 'Evidence URL'),
                    FormTemplate::labeledText('warned_by', 'Warned by', true)
                        ->numeric(),
                    FormTemplate::labeledText('belongsTo', 'Owner', true),
                ])->columns(2),
                Textarea::make('description')
                    ->columnSpanFull(),                
            ]);
    }
}
