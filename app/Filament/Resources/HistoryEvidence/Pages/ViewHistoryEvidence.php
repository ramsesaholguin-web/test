<?php

namespace App\Filament\Resources\HistoryEvidence\Pages;

use App\Filament\Resources\HistoryEvidence\HistoryEvidenceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHistoryEvidence extends ViewRecord
{
    protected static string $resource = HistoryEvidenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
