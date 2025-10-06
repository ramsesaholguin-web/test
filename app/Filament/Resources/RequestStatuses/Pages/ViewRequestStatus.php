<?php

namespace App\Filament\Resources\RequestStatuses\Pages;

use App\Filament\Resources\RequestStatuses\RequestStatusResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRequestStatus extends ViewRecord
{
    protected static string $resource = RequestStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
