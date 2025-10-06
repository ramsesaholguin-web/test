<?php

namespace App\Filament\Resources\AccountStatuses;

use App\Filament\Resources\AccountStatuses\Pages\CreateAccountStatuses;
use App\Filament\Resources\AccountStatuses\Pages\EditAccountStatuses;
use App\Filament\Resources\AccountStatuses\Pages\ListAccountStatuses;
use App\Filament\Resources\AccountStatuses\Pages\ViewAccountStatuses;
use App\Filament\Resources\AccountStatuses\Schemas\AccountStatusesForm;
use App\Filament\Resources\AccountStatuses\Schemas\AccountStatusesInfolist;
use App\Filament\Resources\AccountStatuses\Tables\AccountStatusesTable;
use App\Models\AccountStatuses;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AccountStatusesResource extends Resource
{
    protected static ?string $model = AccountStatuses::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AccountStatusesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AccountStatusesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AccountStatusesTable::configure($table);
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
            'index' => ListAccountStatuses::route('/'),
            'create' => CreateAccountStatuses::route('/create'),
            'view' => ViewAccountStatuses::route('/{record}'),
            'edit' => EditAccountStatuses::route('/{record}/edit'),
        ];
    }
}
