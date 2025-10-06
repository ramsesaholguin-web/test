<?php

namespace App\Filament\Resources\EvidenceTypes\Pages;

use App\Filament\Resources\EvidenceTypes\EvidenceTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEvidenceType extends ViewRecord
{
    protected static string $resource = EvidenceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
