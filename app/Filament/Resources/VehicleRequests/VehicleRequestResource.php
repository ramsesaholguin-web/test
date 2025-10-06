<?php

namespace App\Filament\Resources\VehicleRequests;

use App\Filament\Resources\VehicleRequests\Pages\CreateVehicleRequest;
use App\Filament\Resources\VehicleRequests\Pages\EditVehicleRequest;
use App\Filament\Resources\VehicleRequests\Pages\ListVehicleRequests;
use App\Filament\Resources\VehicleRequests\Pages\ViewVehicleRequest;
use App\Filament\Resources\VehicleRequests\Schemas\VehicleRequestForm;
use App\Filament\Resources\VehicleRequests\Schemas\VehicleRequestInfolist;
use App\Filament\Resources\VehicleRequests\Tables\VehicleRequestsTable;
use App\Models\VehicleRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VehicleRequestResource extends Resource
{
    protected static ?string $model = VehicleRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'VehicleRequest';

    public static function form(Schema $schema): Schema
    {
        return VehicleRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VehicleRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleRequestsTable::configure($table);
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
            'index' => ListVehicleRequests::route('/'),
            'create' => CreateVehicleRequest::route('/create'),
            'view' => ViewVehicleRequest::route('/{record}'),
            'edit' => EditVehicleRequest::route('/{record}/edit'),
        ];
    }
}
