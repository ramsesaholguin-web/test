<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extraer el rol del array de datos antes de crear el usuario
        // El rol no es un campo del modelo User, así que lo removemos
        $role = $data['role'] ?? 'usuario';
        unset($data['role']);
        
        // Guardar el rol en una propiedad para usarlo después
        $this->roleToAssign = $role;
        
        return $data;
    }

    protected function afterCreate(): void
    {
        // Asignar el rol después de crear el usuario
        if (isset($this->roleToAssign)) {
            $this->record->assignRole($this->roleToAssign);
        }
    }
}
