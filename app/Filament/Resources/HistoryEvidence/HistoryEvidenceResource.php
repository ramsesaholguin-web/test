<?php

namespace App\Filament\Resources\HistoryEvidence;

use App\Filament\Resources\HistoryEvidence\Pages\CreateHistoryEvidence;
use App\Filament\Resources\HistoryEvidence\Pages\EditHistoryEvidence;
use App\Filament\Resources\HistoryEvidence\Pages\ListHistoryEvidence;
use App\Filament\Resources\HistoryEvidence\Pages\ViewHistoryEvidence;
use App\Filament\Resources\HistoryEvidence\Schemas\HistoryEvidenceForm;
use App\Filament\Resources\HistoryEvidence\Schemas\HistoryEvidenceInfolist;
use App\Filament\Resources\HistoryEvidence\Tables\HistoryEvidenceTable;
use App\Models\HistoryEvidence;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HistoryEvidenceResource extends Resource
{
    protected static ?string $model = HistoryEvidence::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static UnitEnum|string|null $navigationGroup = 'Vehicles';

    protected static ?string $recordTitleAttribute = 'HistoryEvidence';

    public static function form(Schema $schema): Schema
    {
        return HistoryEvidenceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HistoryEvidenceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HistoryEvidenceTable::configure($table);
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
            'index' => ListHistoryEvidence::route('/'),
            'create' => CreateHistoryEvidence::route('/create'),
            'view' => ViewHistoryEvidence::route('/{record}'),
            'edit' => EditHistoryEvidence::route('/{record}/edit'),
        ];
    }
}
