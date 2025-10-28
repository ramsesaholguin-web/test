<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use App\Models\Warning;
use Filament\Resources\RelationManagers\RelationManager;

class WarningsRelationManager extends RelationManager
{
    protected static string $relationship = 'warnings';

    protected static ?string $recordTitleAttribute = 'description';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schema\Select::make('warning_type_id')
                    ->label('Warning Type')
                    ->relationship('warningType', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Schema\DatePicker::make('warning_date')
                    ->label('Warning Date')
                    ->required(),
                Schema\Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Schema\TextInput::make('evidence_url')
                    ->label('Evidence URL')
                    ->url(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('warning_type.name')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('warning_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('warned_by')
                    ->label('Warned By')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

