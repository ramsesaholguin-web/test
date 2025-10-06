<?php

namespace App\Filament\Resources\RequestStatuses\Pages;

use App\Filament\Resources\RequestStatuses\RequestStatusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRequestStatuses extends ListRecords
{
    protected static string $resource = RequestStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
