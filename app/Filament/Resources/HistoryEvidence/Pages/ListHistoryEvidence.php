<?php

namespace App\Filament\Resources\HistoryEvidence\Pages;

use App\Filament\Resources\HistoryEvidence\HistoryEvidenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHistoryEvidence extends ListRecords
{
    protected static string $resource = HistoryEvidenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
