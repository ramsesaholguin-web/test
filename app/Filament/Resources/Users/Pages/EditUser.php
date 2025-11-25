<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * Mutate form data before saving
     * Remove password from data if it's empty (to keep current password)
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Si el password está vacío, no lo actualizamos
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Extraer el rol del array de datos antes de guardar
        // El rol no es un campo del modelo User, así que lo removemos
        $role = $data['role'] ?? null;
        unset($data['role']);
        
        // Guardar el rol en una propiedad para usarlo después
        if ($role) {
            $this->roleToAssign = $role;
        }

        return $data;
    }

    protected function afterSave(): void
    {
        // Actualizar el rol después de guardar el usuario
        if (isset($this->roleToAssign)) {
            // Remover todos los roles actuales y asignar el nuevo
            $this->record->syncRoles([$this->roleToAssign]);
        }
    }
}
