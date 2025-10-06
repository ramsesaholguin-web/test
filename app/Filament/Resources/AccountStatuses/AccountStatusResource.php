<?php

namespace App\Filament\Resources\AccountStatuses;

use App\Filament\Resources\AccountStatuses\Pages\CreateAccountStatus;
use App\Filament\Resources\AccountStatuses\Pages\EditAccountStatus;
use App\Filament\Resources\AccountStatuses\Pages\ListAccountStatuses;
use App\Filament\Resources\AccountStatuses\Schemas\AccountStatusForm;
use App\Filament\Resources\AccountStatuses\Tables\AccountStatusesTable;
use App\Models\AccountStatus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AccountStatusResource extends Resource
{
    protected static ?string $model = AccountStatus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'AccountStatus';

    public static function form(Schema $schema): Schema
    {
        return AccountStatusForm::configure($schema);
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
            'create' => CreateAccountStatus::route('/create'),
            'edit' => EditAccountStatus::route('/{record}/edit'),
        ];
    }
}
