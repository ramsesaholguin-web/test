# Sistema de Gestión de Flotilla de Vehículos

Sistema completo desarrollado en Laravel con Filament para la gestión integral de una flotilla de vehículos. El sistema permite administrar vehículos, solicitudes de uso, mantenimientos, advertencias a usuarios, documentos y historial de uso.

---

## 📋 Tabla de Contenidos

1. [Características Principales](#características-principales)
2. [Requisitos del Sistema](#requisitos-del-sistema)
3. [Instalación](#instalación)
4. [Módulos del Sistema](#módulos-del-sistema)
5. [Estado de Implementación](#estado-de-implementación)
6. [Tecnologías Utilizadas](#tecnologías-utilizadas)
7. [Documentación Adicional](#documentación-adicional)

---

## ✨ Características Principales

### 🚗 Gestión de Vehículos
- Registro completo de vehículos con información detallada (marca, modelo, placa, VIN, kilometraje, etc.)
- Control de estados de vehículos (Activo, En Mantenimiento, No Disponible)
- Gestión de tipos de combustible
- Documentos asociados (seguros, revisiones técnicas, etc.)
- Widgets de estadísticas y visualización

### 📝 Sistema de Solicitudes
- **Creación de solicitudes** por usuarios con selección de fechas y vehículos
- **Validación en tiempo real** de disponibilidad de vehículos
- **Selector reactivo** que filtra vehículos disponibles según las fechas seleccionadas
- **Validaciones robustas**:
  - Fechas válidas (no pasadas, retorno > salida)
  - Rango máximo de 90 días
  - Prevención de solapamientos con solicitudes aprobadas
  - Verificación de estado del vehículo
- **Estados de solicitud**: Pendiente, Aprobada, Rechazada
- **Autorización por usuario**: Los usuarios solo ven sus propias solicitudes
- **Edición de solicitudes pendientes** con revalidación automática

### 🔧 Gestión de Mantenimientos
- Registro de mantenimientos realizados
- Tipos de mantenimiento (preventivo, correctivo, etc.)
- Control de costos y talleres
- Programación de próximos mantenimientos
- Relación con kilometraje de vehículos

### ⚠️ Sistema de Advertencias
- Registro de advertencias a usuarios
- Tipos de advertencias configurables
- Evidencias adjuntas
- Historial de advertencias por usuario

### 📄 Documentos de Vehículos
- Almacenamiento de documentos
- Tipos de documentos configurables
- Control de fechas de vencimiento
- Alertas de documentos próximos a vencer

### 📊 Historial de Uso
- Registro de uso real de vehículos
- Evidencias de uso (fotos, documentos)
- Relación con solicitudes aprobadas

### 👥 Gestión de Usuarios
- Registro y gestión de usuarios
- Estados de cuenta (Activo, Inactivo, Suspendido)
- Relación con solicitudes y advertencias

---

## 💻 Requisitos del Sistema

### Requisitos Mínimos
- **PHP**: 8.2 o superior
- **Composer**: 2.0 o superior
- **Node.js**: 18.0 o superior (para Vite)
- **NPM**: 9.0 o superior
- **Base de datos**: SQLite (desarrollo) o MySQL/PostgreSQL (producción)

### Extensiones PHP Requeridas
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PCRE
- PDO
- Tokenizer
- XML

---

## 🚀 Instalación

### 1. Clonar el Repositorio

```bash
git clone <url-del-repositorio>
cd test
```

### 2. Instalar Dependencias

```bash
# Instalar dependencias de PHP
composer install

# Instalar dependencias de Node.js
npm install
```

### 3. Configurar el Entorno

```bash
# Copiar archivo de entorno
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 4. Configurar Base de Datos

Editar el archivo `.env` y configurar la base de datos:

```env
DB_CONNECTION=sqlite
# O para MySQL/PostgreSQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=nombre_base_datos
# DB_USERNAME=usuario
# DB_PASSWORD=contraseña
```

Para SQLite, crear el archivo de base de datos:

```bash
touch database/database.sqlite
```

### 5. Ejecutar Migraciones y Seeders

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders (datos iniciales)
php artisan db:seed
```

### 6. Compilar Assets

```bash
# Desarrollo
npm run dev

# Producción
npm run build
```

### 7. Iniciar el Servidor

```bash
# Servidor de desarrollo
php artisan serve

# O usar el comando dev que inicia todo (servidor, queue, vite)
composer run dev
```

### 8. Acceder al Sistema

Abrir en el navegador: `http://localhost:8000/admin`

**Nota**: Necesitarás crear un usuario administrador. Puedes hacerlo ejecutando:

```bash
php artisan make:filament-user
```

---

## 🏗️ Módulos del Sistema

### 1. Vehículos (`Vehicles`)
- CRUD completo de vehículos
- Gestión de estados y tipos de combustible
- Relación con documentos, mantenimientos y solicitudes
- Widgets de estadísticas

### 2. Solicitudes de Vehículos (`VehicleRequests`) ⭐ Módulo Principal
- Formulario reactivo de creación
- Validación de disponibilidad en tiempo real
- Filtrado automático de vehículos disponibles
- Tabla con filtros y búsqueda avanzada
- Autorización por usuario
- Widget de estadísticas de solicitudes

### 3. Mantenimientos (`Maintenances`)
- Registro de mantenimientos
- Tipos de mantenimiento configurables
- Control de costos y fechas
- Widget de estadísticas

### 4. Advertencias (`Warnings`)
- Sistema de advertencias a usuarios
- Tipos configurables
- Evidencias adjuntas
- Historial por usuario

### 5. Documentos de Vehículos (`VehicleDocuments`)
- Almacenamiento de documentos
- Tipos configurables
- Control de vencimientos

### 6. Historial de Uso (`VehicleUsageHistory`)
- Registro de uso real
- Evidencias de uso
- Relación con solicitudes

### 7. Usuarios (`Users`)
- Gestión de usuarios
- Estados de cuenta
- Relación con solicitudes y advertencias

### 8. Configuración
- Estados de vehículos (`VehicleStatuses`)
- Estados de solicitudes (`RequestStatuses`)
- Tipos de combustible (`FuelTypes`)
- Tipos de mantenimiento (`MaintenanceTypes`)
- Tipos de advertencias (`WarningTypes`)
- Tipos de evidencias (`EvidenceTypes`)
- Estados de cuenta (`AccountStatuses`, `UserStatuses`)

---

## 📊 Estado de Implementación

### ✅ Completado (Fases 1-4)

#### Configuración Básica
- ✅ Estructura completa de base de datos
- ✅ Migraciones para todas las tablas
- ✅ Modelos Eloquent con relaciones
- ✅ Seeders para datos iniciales
- ✅ Recursos Filament para todos los módulos

#### Sistema de Solicitudes
- ✅ Formulario completo de creación
- ✅ Selector reactivo de vehículos
- ✅ Validación de disponibilidad en tiempo real
- ✅ Validaciones del servidor:
  - Verificación de disponibilidad
  - Validación de solapamiento de fechas
  - Prevención de solicitudes duplicadas
  - Validación de fechas (no pasadas, orden correcto, rango máximo)
- ✅ Vista de lista con filtros y búsqueda
- ✅ Autorización por usuario (usuarios solo ven sus solicitudes)
- ✅ Filtrado automático por rol (admins ven todas, usuarios solo las suyas)
- ✅ Edición de solicitudes pendientes
- ✅ Acciones de aprobar/rechazar solicitudes
- ✅ Cancelación de solicitudes (usuarios: pendientes, admins: pendientes/aprobadas)
- ✅ Widget de estadísticas

#### Modelos y Validaciones
- ✅ Métodos de validación en `VehicleRequest`
- ✅ Método `isAvailableForDates()` en `Vehicle`
- ✅ Scope `availableForDates()` para consultas
- ✅ Lógica de solapamiento de fechas

#### Interfaz de Usuario
- ✅ Badges de estado con colores
- ✅ Información amigable (nombres en lugar de IDs)
- ✅ Filtros por estado y vehículo
- ✅ Búsqueda mejorada
- ✅ Manejo seguro de valores null
- ✅ Estados vacíos con mensajes descriptivos

#### Sistema de Roles y Permisos
- ✅ Implementado con Filament Shield y Spatie Permission
- ✅ Roles configurados: `admin` y `usuario`
- ✅ Permisos granulares por recurso
- ✅ Recursos Users y Vehicles ocultos para usuarios regulares
- ✅ Usuarios regulares solo ven sus propias solicitudes
- ✅ Administradores tienen acceso completo

#### Gestión de Estados de Solicitudes
- ✅ Estados: Pendiente, Aprobada, Rechazada, **Cancelled**
- ✅ Cancelación de solicitudes implementada
- ✅ Validaciones por rol (usuarios solo pueden cancelar sus pendientes)
- ✅ Registro de quién y cuándo canceló

### ⏳ Pendiente (Fases 5-6)

#### Panel de Administración
- ✅ Vista administrativa con todas las solicitudes (implementado con filtrado por rol)
- ✅ Acciones de aprobar/rechazar desde la interfaz (implementado)
- ⏳ Filtros avanzados para administradores
- ⏳ Estadísticas y reportes administrativos

#### Mejoras Adicionales
- ⏳ Notificaciones por email
- ⏳ Historial de cambios
- ⏳ Completar solicitudes
- ⏳ Reportes avanzados
- ⏳ Integración con calendario externo

---

## 🛠️ Tecnologías Utilizadas

### Backend
- **Laravel 12.0**: Framework PHP moderno y robusto
- **PHP 8.2+**: Lenguaje de programación del lado del servidor
- **Filament 4.0**: Panel de administración moderno y elegante
- **SQLite**: Base de datos (configurable para producción con MySQL/PostgreSQL)

### Frontend
- **Filament UI**: Interfaz de usuario construida con Livewire
- **Tailwind CSS 4.0**: Framework de CSS utility-first
- **Alpine.js**: Framework JavaScript ligero (incluido en Filament)
- **Vite 7.0**: Build tool y bundler moderno

### Paquetes Adicionales
- **Guava Calendar 2.0**: Widget de calendario para visualización de eventos
- **Filament Shield 4.0**: Sistema de roles y permisos para Filament
- **Spatie Permission 6.0**: Sistema de gestión de roles y permisos
- **Laravel Tinker**: REPL para interactuar con la aplicación
- **Faker**: Generación de datos de prueba

### Herramientas de Desarrollo
- **Laravel Pint**: Code style fixer
- **PHPUnit**: Framework de testing
- **Laravel Pail**: Monitoreo de logs en tiempo real
- **Laravel Sail**: Entorno de desarrollo con Docker

---

## 📚 Documentación Adicional

Para más detalles sobre la implementación, consultar:

- **`DOCUMENTACION_PROYECTO.md`** - Documentación completa del proyecto
- **`ASIGNAR_ROLES.md`** - Guía para asignar roles a usuarios
- **`docs/guia-implementacion-solicitudes.md`** - Guía completa de implementación del sistema de solicitudes
- **`docs/widgets-explicacion.md`** - Documentación de widgets
- **`docs/widgets-explicacion-practica.md`** - Explicación práctica de widgets
- **`docs/form-consistency-report.md`** - Reporte de consistencia de formularios

---

## 🔒 Seguridad

### Implementado
- ✅ Autenticación de usuarios
- ✅ **Sistema de roles y permisos** (Filament Shield + Spatie Permission)
- ✅ **Roles**: Admin y Usuario con permisos granulares
- ✅ Autorización por usuario (solo ven sus solicitudes)
- ✅ Recursos protegidos (Users y Vehicles solo para admins)
- ✅ Validación de datos de entrada
- ✅ Protección CSRF
- ✅ Sanitización de datos
- ✅ Prevención de SQL Injection (Eloquent)
- ✅ Prevención de XSS (Filament)

### Pendiente
- ⏳ Auditoría de acciones
- ⏳ Logs de seguridad
- ⏳ Rate limiting
- ⏳ Autenticación de dos factores (2FA)

---

## 🧪 Testing

### Estado Actual
- ⏳ Tests unitarios pendientes
- ⏳ Tests de integración pendientes
- ⏳ Tests de características pendientes

### Cobertura Objetivo
- Modelos: 80%+
- Validaciones: 100%
- Recursos Filament: 70%+
- APIs: 80%+ (cuando se implementen)

---

## 📝 Convenciones de Código

- **PSR-12** coding standard
- **Laravel Pint** para formateo automático
- Nombres en inglés para código
- Comentarios en español para documentación

---

## 🚀 Comandos Útiles

```bash
# Desarrollo
composer run dev              # Inicia servidor, queue y vite
php artisan serve            # Solo servidor
npm run dev                  # Solo Vite

# Base de datos
php artisan migrate           # Ejecutar migraciones
php artisan migrate:fresh     # Resetear base de datos
php artisan db:seed          # Ejecutar seeders

# Filament
php artisan make:filament-user    # Crear usuario
php artisan filament:upgrade     # Actualizar Filament

# Código
php artisan pint             # Formatear código
php artisan test             # Ejecutar tests
```

---

## 📄 Licencia

Este proyecto está bajo la licencia MIT.

---

## 👥 Contribuidores

Este proyecto ha sido desarrollado como sistema de gestión de flotilla de vehículos.

### Tecnologías y Recursos
- [Laravel Framework](https://laravel.com)
- [Filament Admin Panel](https://filamentphp.com)
- [Guava Calendar](https://github.com/guava/calendar)
- [Tailwind CSS](https://tailwindcss.com)
- [Heroicons](https://heroicons.com)

---

## 📞 Soporte

Para más información o soporte, consultar la documentación adicional en la carpeta `docs/`.

### Recursos Externos
- [Documentación de Laravel](https://laravel.com/docs)
- [Documentación de Filament](https://filamentphp.com/docs)
- [Documentación de Guava Calendar](https://github.com/guava/calendar)

---

**Última actualización**: Diciembre 2024
**Versión**: 1.1  
**Estado**: Fases 1-4 Completadas ✅ | Fase 5 Parcialmente Completada ✅ | Fase 6 Pendiente ⏳

### 🆕 Nuevas Funcionalidades (v1.1)
- ✅ Sistema de roles y permisos con Filament Shield
- ✅ Cancelación de solicitudes
- ✅ Filtrado automático por usuario según rol
- ✅ Protección de recursos (Users y Vehicles)
