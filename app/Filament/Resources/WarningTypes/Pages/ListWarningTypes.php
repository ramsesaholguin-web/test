<?php

namespace App\Filament\Resources\WarningTypes\Pages;

use App\Filament\Resources\WarningTypes\WarningTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWarningTypes extends ListRecords
{
    protected static string $resource = WarningTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
