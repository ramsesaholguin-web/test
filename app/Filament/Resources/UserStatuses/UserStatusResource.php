<?php

namespace App\Filament\Resources\UserStatuses;

use App\Filament\Resources\UserStatuses\Pages\CreateUserStatus;
use App\Filament\Resources\UserStatuses\Pages\EditUserStatus;
use App\Filament\Resources\UserStatuses\Pages\ListUserStatuses;
use App\Filament\Resources\UserStatuses\Pages\ViewUserStatus;
use App\Filament\Resources\UserStatuses\Schemas\UserStatusForm;
use App\Filament\Resources\UserStatuses\Schemas\UserStatusInfolist;
use App\Filament\Resources\UserStatuses\Tables\UserStatusesTable;
use App\Models\UserStatus;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserStatusResource extends Resource
{
    protected static ?string $model = UserStatus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static UnitEnum|string|null $navigationGroup = 'Users';

    protected static ?string $recordTitleAttribute = 'UserStatus';

    public static function form(Schema $schema): Schema
    {
        return UserStatusForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserStatusInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserStatusesTable::configure($table);
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
            'index' => ListUserStatuses::route('/'),
            'create' => CreateUserStatus::route('/create'),
            'view' => ViewUserStatus::route('/{record}'),
            'edit' => EditUserStatus::route('/{record}/edit'),
        ];
    }
}
