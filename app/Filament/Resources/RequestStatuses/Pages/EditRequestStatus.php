<?php

namespace App\Filament\Resources\RequestStatuses\Pages;

use App\Filament\Resources\RequestStatuses\RequestStatusResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRequestStatus extends EditRecord
{
    protected static string $resource = RequestStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
