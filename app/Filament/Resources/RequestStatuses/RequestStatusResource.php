<?php

namespace App\Filament\Resources\RequestStatuses;

use App\Filament\Resources\RequestStatuses\Pages\CreateRequestStatus;
use App\Filament\Resources\RequestStatuses\Pages\EditRequestStatus;
use App\Filament\Resources\RequestStatuses\Pages\ListRequestStatuses;
use App\Filament\Resources\RequestStatuses\Pages\ViewRequestStatus;
use App\Filament\Resources\RequestStatuses\Schemas\RequestStatusForm;
use App\Filament\Resources\RequestStatuses\Schemas\RequestStatusInfolist;
use App\Filament\Resources\RequestStatuses\Tables\RequestStatusesTable;
use App\Models\RequestStatus;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RequestStatusResource extends Resource
{
    protected static ?string $model = RequestStatus::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-folder';

    protected static UnitEnum|string|null $navigationGroup = 'Users';

    protected static ?string $recordTitleAttribute = 'RequestStatus';

    public static function form(Schema $schema): Schema
    {
        return RequestStatusForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RequestStatusInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RequestStatusesTable::configure($table);
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
            'index' => ListRequestStatuses::route('/'),
            'create' => CreateRequestStatus::route('/create'),
            'view' => ViewRequestStatus::route('/{record}'),
            'edit' => EditRequestStatus::route('/{record}/edit'),
        ];
    }
}
