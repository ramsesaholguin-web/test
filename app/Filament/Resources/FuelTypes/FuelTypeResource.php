<?php

namespace App\Filament\Resources\FuelTypes;

use App\Filament\Resources\FuelTypes\Pages\CreateFuelType;
use App\Filament\Resources\FuelTypes\Pages\EditFuelType;
use App\Filament\Resources\FuelTypes\Pages\ListFuelTypes;
use App\Filament\Resources\FuelTypes\Pages\ViewFuelType;
use App\Filament\Resources\FuelTypes\Schemas\FuelTypeForm;
use App\Filament\Resources\FuelTypes\Schemas\FuelTypeInfolist;
use App\Filament\Resources\FuelTypes\Tables\FuelTypesTable;
use App\Models\FuelType;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FuelTypeResource extends Resource
{
    protected static ?string $model = FuelType::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-fire';

    protected static UnitEnum|string|null $navigationGroup = 'Vehicles';

    protected static ?string $recordTitleAttribute = 'FuelType';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return FuelTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FuelTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FuelTypesTable::configure($table);
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
            'index' => ListFuelTypes::route('/'),
            'create' => CreateFuelType::route('/create'),
            'view' => ViewFuelType::route('/{record}'),
            'edit' => EditFuelType::route('/{record}/edit'),
        ];
    }
}
