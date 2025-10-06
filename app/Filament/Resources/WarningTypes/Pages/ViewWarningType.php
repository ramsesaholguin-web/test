<?php

namespace App\Filament\Resources\WarningTypes\Pages;

use App\Filament\Resources\WarningTypes\WarningTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWarningType extends ViewRecord
{
    protected static string $resource = WarningTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
