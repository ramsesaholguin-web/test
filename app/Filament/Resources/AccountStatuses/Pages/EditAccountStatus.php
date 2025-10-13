<?php

namespace App\Filament\Resources\AccountStatuses\Pages;

use App\Filament\Resources\AccountStatuses\AccountStatusesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAccountStatus extends EditRecord
{
    protected static string $resource = AccountStatusesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
