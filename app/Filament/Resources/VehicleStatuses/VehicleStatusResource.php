<?php

namespace App\Filament\Resources\VehicleStatuses;

use App\Filament\Resources\VehicleStatuses\Pages\CreateVehicleStatus;
use App\Filament\Resources\VehicleStatuses\Pages\EditVehicleStatus;
use App\Filament\Resources\VehicleStatuses\Pages\ListVehicleStatuses;
use App\Filament\Resources\VehicleStatuses\Pages\ViewVehicleStatus;
use App\Filament\Resources\VehicleStatuses\Schemas\VehicleStatusForm;
use App\Filament\Resources\VehicleStatuses\Schemas\VehicleStatusInfolist;
use App\Filament\Resources\VehicleStatuses\Tables\VehicleStatusesTable;
use App\Models\VehicleStatus;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VehicleStatusResource extends Resource
{
    protected static ?string $model = VehicleStatus::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-folder';

    protected static UnitEnum|string|null $navigationGroup = 'Vehicles';

    protected static ?string $recordTitleAttribute = 'VehicleStatus';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return VehicleStatusForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VehicleStatusInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleStatusesTable::configure($table);
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
            'index' => ListVehicleStatuses::route('/'),
            'create' => CreateVehicleStatus::route('/create'),
            'view' => ViewVehicleStatus::route('/{record}'),
            'edit' => EditVehicleStatus::route('/{record}/edit'),
        ];
    }
}
