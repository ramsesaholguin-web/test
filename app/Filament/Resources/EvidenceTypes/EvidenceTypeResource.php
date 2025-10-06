<?php

namespace App\Filament\Resources\EvidenceTypes;

use App\Filament\Resources\EvidenceTypes\Pages\CreateEvidenceType;
use App\Filament\Resources\EvidenceTypes\Pages\EditEvidenceType;
use App\Filament\Resources\EvidenceTypes\Pages\ListEvidenceTypes;
use App\Filament\Resources\EvidenceTypes\Pages\ViewEvidenceType;
use App\Filament\Resources\EvidenceTypes\Schemas\EvidenceTypeForm;
use App\Filament\Resources\EvidenceTypes\Schemas\EvidenceTypeInfolist;
use App\Filament\Resources\EvidenceTypes\Tables\EvidenceTypesTable;
use App\Models\EvidenceType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EvidenceTypeResource extends Resource
{
    protected static ?string $model = EvidenceType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'EvidenceType';

    public static function form(Schema $schema): Schema
    {
        return EvidenceTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EvidenceTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EvidenceTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvidenceTypes::route('/'),
            'create' => CreateEvidenceType::route('/create'),
            'view' => ViewEvidenceType::route('/{record}'),
            'edit' => EditEvidenceType::route('/{record}/edit'),
        ];
    }
}
