<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos básicos para recursos
        $permissions = [
            // Permisos para Users
            'view_any_user_resource',
            'view_user_resource',
            'create_user_resource',
            'update_user_resource',
            'delete_user_resource',
            
            // Permisos para Vehicles
            'view_any_vehicle_resource',
            'view_vehicle_resource',
            'create_vehicle_resource',
            'update_vehicle_resource',
            'delete_vehicle_resource',
            
            // Permisos para VehicleRequests (usuarios pueden ver y crear)
            'view_any_vehicle_request_resource',
            'view_vehicle_request_resource',
            'create_vehicle_request_resource',
            'update_vehicle_request_resource',
            'delete_vehicle_request_resource',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Crear rol de Administrador
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        // Crear rol de Usuario
        $userRole = Role::firstOrCreate(['name' => 'usuario', 'guard_name' => 'web']);
        // Usuarios solo pueden ver y crear solicitudes
        $userRole->givePermissionTo([
            'view_any_vehicle_request_resource',
            'view_vehicle_request_resource',
            'create_vehicle_request_resource',
            'update_vehicle_request_resource', // Solo sus propias solicitudes pendientes
        ]);

        $this->command->info('Roles y permisos creados exitosamente!');
        $this->command->info('Roles: admin, usuario');
    }
}
