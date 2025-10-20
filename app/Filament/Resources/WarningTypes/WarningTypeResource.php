<?php

namespace App\Filament\Resources\WarningTypes;

use App\Filament\Resources\WarningTypes\Pages\CreateWarningType;
use App\Filament\Resources\WarningTypes\Pages\EditWarningType;
use App\Filament\Resources\WarningTypes\Pages\ListWarningTypes;
use App\Filament\Resources\WarningTypes\Pages\ViewWarningType;
use App\Filament\Resources\WarningTypes\Schemas\WarningTypeForm;
use App\Filament\Resources\WarningTypes\Schemas\WarningTypeInfolist;
use App\Filament\Resources\WarningTypes\Tables\WarningTypesTable;
use App\Models\WarningType;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WarningTypeResource extends Resource
{
    protected static ?string $model = WarningType::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-folder';

    protected static UnitEnum|string|null $navigationGroup = 'Users';

    public static function form(Schema $schema): Schema
    {
        return WarningTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WarningTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WarningTypesTable::configure($table);
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
            'index' => ListWarningTypes::route('/'),
            'create' => CreateWarningType::route('/create'),
            'view' => ViewWarningType::route('/{record}'),
            'edit' => EditWarningType::route('/{record}/edit'),
        ];
    }
}
