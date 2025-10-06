<?php

namespace App\Filament\Resources\UserStatuses\Pages;

use App\Filament\Resources\UserStatuses\UserStatusResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserStatus extends CreateRecord
{
    protected static string $resource = UserStatusResource::class;
}
