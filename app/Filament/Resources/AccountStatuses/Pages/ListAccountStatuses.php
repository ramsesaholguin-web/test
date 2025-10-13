<?php

namespace App\Filament\Resources\AccountStatuses\Pages;

use App\Filament\Resources\AccountStatuses\AccountStatusesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAccountStatuses extends ListRecords
{
    protected static string $resource = AccountStatusesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
