<?php

namespace App\Filament\Resources\MaintenanceTypes;

use App\Filament\Resources\MaintenanceTypes\Pages\CreateMaintenanceType;
use App\Filament\Resources\MaintenanceTypes\Pages\EditMaintenanceType;
use App\Filament\Resources\MaintenanceTypes\Pages\ListMaintenanceTypes;
use App\Filament\Resources\MaintenanceTypes\Pages\ViewMaintenanceType;
use App\Filament\Resources\MaintenanceTypes\Schemas\MaintenanceTypeForm;
use App\Filament\Resources\MaintenanceTypes\Schemas\MaintenanceTypeInfolist;
use App\Filament\Resources\MaintenanceTypes\Tables\MaintenanceTypesTable;
use App\Models\MaintenanceType;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MaintenanceTypeResource extends Resource
{
    protected static ?string $model = MaintenanceType::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-folder';

    protected static UnitEnum|string|null $navigationGroup = 'Vehicles';

    protected static ?string $recordTitleAttribute = 'MaintenanceType';

    public static function form(Schema $schema): Schema
    {
        return MaintenanceTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MaintenanceTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaintenanceTypesTable::configure($table);
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
            'index' => ListMaintenanceTypes::route('/'),
            'create' => CreateMaintenanceType::route('/create'),
            'view' => ViewMaintenanceType::route('/{record}'),
            'edit' => EditMaintenanceType::route('/{record}/edit'),
        ];
    }
}
