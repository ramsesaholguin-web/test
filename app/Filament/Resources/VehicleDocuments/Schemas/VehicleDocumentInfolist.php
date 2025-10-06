<?php

namespace App\Filament\Resources\VehicleDocuments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VehicleDocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('vehicle.id')
                    ->numeric(),
                TextEntry::make('document_name'),
                TextEntry::make('file_path'),
                TextEntry::make('expiration_date')
                    ->date(),
                TextEntry::make('upload_date')
                    ->dateTime(),
                TextEntry::make('uploaded_by')
                    ->numeric(),
                TextEntry::make('belongsTo'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
