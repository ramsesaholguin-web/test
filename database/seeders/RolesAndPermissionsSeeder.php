<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * NOTA: Este seeder solo crea los roles básicos.
     * Los permisos deben generarse con: php artisan shield:generate
     * Luego, asigna los permisos a los roles desde la interfaz de Shield.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear rol de Super Admin (usado por Filament Shield)
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // Crear rol de Usuario regular
        $userRole = Role::firstOrCreate(['name' => 'usuario', 'guard_name' => 'web']);

        $this->command->info('Roles creados exitosamente!');
        $this->command->info('Roles: super_admin, usuario');
        $this->command->warn('IMPORTANTE: Ejecuta "php artisan shield:generate" para generar los permisos automáticamente.');
        $this->command->warn('Luego, asigna los permisos a los roles desde: /admin/shield/roles');
        $this->command->info('NOTA: El primer usuario que se registre recibirá automáticamente el rol super_admin.');
    }
}
