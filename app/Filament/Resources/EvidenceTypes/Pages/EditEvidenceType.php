<?php

namespace App\Filament\Resources\EvidenceTypes\Pages;

use App\Filament\Resources\EvidenceTypes\EvidenceTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEvidenceType extends EditRecord
{
    protected static string $resource = EvidenceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
