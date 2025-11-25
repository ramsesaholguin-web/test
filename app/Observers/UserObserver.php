<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * Asigna automáticamente el rol super_admin al primer usuario que se registre.
     */
    public function created(User $user): void
    {
        // Si es el primer usuario (no tiene roles y es el único usuario), asignar super_admin
        if (User::count() === 1 && !$user->hasAnyRole(['super_admin', 'usuario'])) {
            $user->assignRole('super_admin');
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
