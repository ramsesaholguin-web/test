<?php

namespace App\Filament\Resources\VehicleUsageHistories;

use App\Filament\Resources\VehicleUsageHistories\Pages\CreateVehicleUsageHistory;
use App\Filament\Resources\VehicleUsageHistories\Pages\EditVehicleUsageHistory;
use App\Filament\Resources\VehicleUsageHistories\Pages\ListVehicleUsageHistories;
use App\Filament\Resources\VehicleUsageHistories\Pages\ViewVehicleUsageHistory;
use App\Filament\Resources\VehicleUsageHistories\Schemas\VehicleUsageHistoryForm;
use App\Filament\Resources\VehicleUsageHistories\Schemas\VehicleUsageHistoryInfolist;
use App\Filament\Resources\VehicleUsageHistories\Tables\VehicleUsageHistoriesTable;
use App\Models\VehicleUsageHistory;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VehicleUsageHistoryResource extends Resource
{
    protected static ?string $model = VehicleUsageHistory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static UnitEnum|string|null $navigationGroup = 'Vehicles';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return VehicleUsageHistoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VehicleUsageHistoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleUsageHistoriesTable::configure($table);
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
            'index' => ListVehicleUsageHistories::route('/'),
            'create' => CreateVehicleUsageHistory::route('/create'),
            'view' => ViewVehicleUsageHistory::route('/{record}'),
            'edit' => EditVehicleUsageHistory::route('/{record}/edit'),
        ];
    }
}
