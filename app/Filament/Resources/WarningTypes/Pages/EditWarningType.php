<?php

namespace App\Filament\Resources\WarningTypes\Pages;

use App\Filament\Resources\WarningTypes\WarningTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWarningType extends EditRecord
{
    protected static string $resource = WarningTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
