<?php

namespace App\Filament\Resources\UserStatuses\Pages;

use App\Filament\Resources\UserStatuses\UserStatusResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUserStatus extends ViewRecord
{
    protected static string $resource = UserStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
