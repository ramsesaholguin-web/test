<?php

namespace App\Filament\Resources\EvidenceTypes\Pages;

use App\Filament\Resources\EvidenceTypes\EvidenceTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEvidenceTypes extends ListRecords
{
    protected static string $resource = EvidenceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
