# Guía para Asignar Roles a Usuarios

## Roles Disponibles

1. **admin**: Acceso completo al sistema, puede ver y gestionar todo
2. **usuario**: Acceso limitado, solo puede ver y crear sus propias solicitudes

## Asignar Roles a Usuarios

### Opción 1: Desde Tinker (Recomendado para desarrollo)

```bash
php artisan tinker
```

Luego ejecuta:

```php
// Obtener un usuario
$user = \App\Models\User::where('email', 'admin@example.com')->first();

// Asignar rol de admin
$user->assignRole('admin');

// O asignar rol de usuario
$user->assignRole('usuario');

// Verificar roles
$user->hasRole('admin'); // true o false
```

### Opción 2: Crear un comando Artisan

```bash
php artisan make:command AssignRole
```

### Opción 3: Desde la base de datos directamente

```sql
-- Obtener el ID del usuario y del rol
SELECT id, name, email FROM users;
SELECT id, name FROM roles;

-- Asignar rol (reemplaza USER_ID y ROLE_ID)
INSERT INTO model_has_roles (role_id, model_type, model_id)
VALUES (ROLE_ID, 'App\\Models\\User', USER_ID);
```

## Verificar Permisos

Los recursos **Users** y **Vehicles** solo son visibles para usuarios con rol `admin`.

Los usuarios con rol `usuario` solo pueden:
- Ver sus propias solicitudes
- Crear nuevas solicitudes
- Editar sus solicitudes pendientes
- Cancelar sus solicitudes pendientes

## Notas Importantes

- El estado "Cancelled" ya existe en el seeder `RequestStatusSeeder`
- Los permisos se generan automáticamente al ejecutar `RolesAndPermissionsSeeder`
- Los roles se crean automáticamente: `admin` y `usuario`

