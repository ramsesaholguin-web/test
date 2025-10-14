<?php

namespace App\Filament\Resources\AccountStatuses\Schemas;

use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;

class AccountStatusesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('Account statuses', [
                        FormTemplate::labeledText('name', 'Name', true),
                    ])->columns(2),
                ]),
            ]);
    }
}
