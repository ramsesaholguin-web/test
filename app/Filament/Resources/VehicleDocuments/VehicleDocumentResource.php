<?php

namespace App\Filament\Resources\VehicleDocuments;

use App\Filament\Resources\VehicleDocuments\Pages\CreateVehicleDocument;
use App\Filament\Resources\VehicleDocuments\Pages\EditVehicleDocument;
use App\Filament\Resources\VehicleDocuments\Pages\ListVehicleDocuments;
use App\Filament\Resources\VehicleDocuments\Pages\ViewVehicleDocument;
use App\Filament\Resources\VehicleDocuments\Schemas\VehicleDocumentForm;
use App\Filament\Resources\VehicleDocuments\Schemas\VehicleDocumentInfolist;
use App\Filament\Resources\VehicleDocuments\Tables\VehicleDocumentsTable;
use App\Models\VehicleDocument;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VehicleDocumentResource extends Resource
{
    protected static ?string $model = VehicleDocument::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document';

    protected static UnitEnum|string|null $navigationGroup = 'Vehicles';

    protected static ?string $recordTitleAttribute = 'VehicleDocument';

    public static function form(Schema $schema): Schema
    {
        return VehicleDocumentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VehicleDocumentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleDocumentsTable::configure($table);
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
            'index' => ListVehicleDocuments::route('/'),
            'create' => CreateVehicleDocument::route('/create'),
            'view' => ViewVehicleDocument::route('/{record}'),
            'edit' => EditVehicleDocument::route('/{record}/edit'),
        ];
    }
}
