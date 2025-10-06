<?php

namespace App\Filament\Resources\HistoryEvidence\Pages;

use App\Filament\Resources\HistoryEvidence\HistoryEvidenceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditHistoryEvidence extends EditRecord
{
    protected static string $resource = HistoryEvidenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
