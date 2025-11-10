# Guía de Widgets en Filament

## ¿Qué son los Widgets?

Los widgets en Filament son componentes visuales que muestran información en el dashboard o en las páginas de recursos. Hay varios tipos de widgets:

## Tipos de Widgets

### 1. StatsOverviewWidget (Widget de Estadísticas)

Muestra tarjetas con números y estadísticas. Es el más común para mostrar métricas.

**Estructura básica:**
```php
<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MiWidget extends StatsOverviewWidget
{
    // Método obligatorio: retorna un array de Stat
    protected function getStats(): array
    {
        // 1. Obtener datos (usando Eloquent)
        $totalUsuarios = User::count();
        
        // 2. Crear y retornar estadísticas
        return [
            Stat::make('Título', $valor)
                ->description('Descripción debajo del número')
                ->descriptionIcon('heroicon-o-icono')  // Icono pequeño
                ->color('primary')  // Color: primary, success, warning, danger, info
                ->icon('heroicon-o-icono-grande')  // Icono grande
                ->url('ruta-opcional'),  // Enlace al hacer clic
        ];
    }
}
```

**Ejemplo real:**
```php
protected function getStats(): array
{
    $totalUsuarios = User::count();
    $usuariosActivos = User::where('active', true)->count();
    
    return [
        Stat::make('Total Usuarios', $totalUsuarios)
            ->description('Registrados en el sistema')
            ->color('primary')
            ->icon('heroicon-o-users'),
        
        Stat::make('Usuarios Activos', $usuariosActivos)
            ->description('Actualmente activos')
            ->color('success')
            ->icon('heroicon-o-check-circle'),
    ];
}
```

**Propiedades de Stat:**
- `make('Título', $valor)` - Título y valor numérico
- `description('texto')` - Texto descriptivo
- `descriptionIcon('icono')` - Icono pequeño junto a la descripción
- `color('primary|success|warning|danger|info')` - Color del widget
- `icon('icono')` - Icono grande principal
- `url('ruta')` - Enlace al hacer clic
- `chart([datos])` - Gráfico (opcional)

---

### 2. TableWidget (Widget de Tabla)

Muestra una tabla con datos. Útil para listar información detallada.

**Estructura básica:**
```php
<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MiTablaWidget extends BaseWidget
{
    // Opcional: orden de aparición
    protected static ?int $sort = 1;
    
    // Opcional: ancho del widget (full = ancho completo)
    protected int | string | array $columnSpan = 'full';
    
    // Método obligatorio: configura la tabla
    public function table(Table $table): Table
    {
        return $table
            // 1. Definir la consulta (query)
            ->query(
                Vehicle::query()
                    ->with(['relaciones'])  // Cargar relaciones
                    ->where('condicion', 'valor')
                    ->orderBy('campo', 'asc')
                    ->limit(10)
            )
            // 2. Definir las columnas
            ->columns([
                TextColumn::make('campo')
                    ->label('Etiqueta')
                    ->searchable()  // Permite buscar
                    ->sortable()    // Permite ordenar
                    ->badge()       // Muestra como badge
                    ->color('primary'),  // Color del badge
            ])
            // 3. Configuraciones opcionales
            ->heading('Título de la Tabla')
            ->description('Descripción de la tabla');
    }
}
```

**Ejemplo real:**
```php
public function table(Table $table): Table
{
    return $table
        ->query(
            Vehicle::query()
                ->with(['status', 'fuelType'])
                ->where('status_id', 1)
                ->orderBy('plate', 'asc')
        )
        ->columns([
            TextColumn::make('plate')
                ->label('Placa')
                ->searchable()
                ->sortable(),
            
            TextColumn::make('brand')
                ->label('Marca')
                ->searchable(),
            
            TextColumn::make('status.name')
                ->label('Estado')
                ->badge()
                ->color(fn ($record) => 
                    $record->status->name === 'Disponible' ? 'success' : 'warning'
                ),
        ])
        ->heading('Vehículos Disponibles');
}
```

**Tipos de Columnas:**
- `TextColumn::make('campo')` - Texto simple
- `TextColumn::make('relacion.campo')` - Campo de relación
- `IconColumn::make('campo')` - Icono
- `ImageColumn::make('campo')` - Imagen
- `BooleanColumn::make('campo')` - Checkbox
- `BadgeColumn::make('campo')` - Badge

**Métodos comunes de columnas:**
- `label('texto')` - Etiqueta personalizada
- `searchable()` - Permite buscar en esta columna
- `sortable()` - Permite ordenar
- `badge()` - Muestra como badge
- `color('color')` - Color del badge/texto
- `dateTime('formato')` - Formato de fecha
- `money('moneda')` - Formato de dinero
- `numeric()` - Formato numérico
- `limit(50)` - Limita caracteres
- `wrap()` - Permite texto largo

---

### 3. CalendarWidget (Widget de Calendario)

Muestra eventos en un calendario. Requiere el paquete `guava/calendar`.

**Estructura básica:**
```php
<?php

namespace App\Filament\Widgets;

use App\Models\VehicleRequest;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\FetchInfo;

class MiCalendario extends CalendarWidget
{
    // Título del calendario
    protected string | null $heading = 'Mi Calendario';
    
    // Habilitar clics en eventos
    protected bool $eventClickEnabled = true;
    
    // Método obligatorio: obtiene los eventos
    public function getEvents(FetchInfo $info): Collection | array | Builder
    {
        return VehicleRequest::query()
            ->whereBetween('fecha', [$info->start, $info->end])
            ->get()
            ->map(function ($request) {
                return CalendarEvent::make()
                    ->key($request->id)  // ID único
                    ->title($request->titulo)
                    ->start($request->fecha_inicio)
                    ->end($request->fecha_fin)
                    ->backgroundColor('#color')
                    ->extendedProps([
                        'datos' => 'adicionales',
                    ]);
            });
    }
}
```

---

## Dónde Colocar los Widgets

### 1. Dashboard (Inicio)

Los widgets en `app/Filament/Widgets/` se descubren automáticamente y aparecen en el dashboard.

**Ubicación:** `app/Filament/Widgets/MiWidget.php`

**Descubrimiento automático:**
```php
// En AdminPanelProvider.php
->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
```

### 2. Recursos (Páginas de Listado)

Los widgets específicos de un recurso van en la carpeta del recurso.

**Ubicación:** `app/Filament/Resources/Users/Widgets/MiWidget.php`

**Registro en la página:**
```php
// En ListUsers.php
protected function getHeaderWidgets(): array
{
    return [
        UsuariosStats::class,
    ];
}
```

---

## Flujo de Trabajo para Crear un Widget

### Paso 1: Decidir el tipo
- ¿Métricas simples? → `StatsOverviewWidget`
- ¿Lista de datos? → `TableWidget`
- ¿Eventos en calendario? → `CalendarWidget`

### Paso 2: Crear el archivo
- Dashboard: `app/Filament/Widgets/MiWidget.php`
- Recurso: `app/Filament/Resources/Recurso/Widgets/MiWidget.php`

### Paso 3: Extender la clase base
```php
use Filament\Widgets\StatsOverviewWidget;
class MiWidget extends StatsOverviewWidget { }
```

### Paso 4: Implementar el método obligatorio
- `StatsOverviewWidget` → `getStats()`
- `TableWidget` → `table()`
- `CalendarWidget` → `getEvents()`

### Paso 5: Obtener datos
```php
$datos = Model::query()
    ->where('condicion', 'valor')
    ->count();
```

### Paso 6: Configurar y retornar
```php
return [
    Stat::make('Título', $datos)
        ->description('Descripción')
        ->color('primary'),
];
```

---

## Ejemplos Completos

### Widget de Estadísticas Simple
```php
<?php
namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalUsuarios extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Usuarios', User::count())
                ->description('Registrados en el sistema')
                ->color('primary')
                ->icon('heroicon-o-users'),
        ];
    }
}
```

### Widget de Tabla Completo
```php
<?php
namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class VehiculosDisponibles extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Vehicle::query()
                    ->with(['status'])
                    ->whereHas('status', fn($q) => $q->where('name', 'Disponible'))
                    ->orderBy('plate')
            )
            ->columns([
                TextColumn::make('plate')
                    ->label('Placa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('brand')
                    ->label('Marca'),
                TextColumn::make('status.name')
                    ->label('Estado')
                    ->badge()
                    ->color('success'),
            ])
            ->heading('Vehículos Disponibles');
    }
}
```

---

## Consejos y Mejores Prácticas

1. **Usar relaciones eager loading:**
   ```php
   ->with(['relacion1', 'relacion2'])
   ```

2. **Optimizar consultas:**
   ```php
   ->select('campo1', 'campo2')  // Solo campos necesarios
   ->limit(10)  // Limitar resultados
   ```

3. **Colores consistentes:**
   - `primary` - Información general
   - `success` - Éxito/positivo
   - `warning` - Advertencia
   - `danger` - Error/crítico
   - `info` - Información adicional

4. **Iconos Heroicons:**
   - Usar `heroicon-o-` para outline
   - Usar `heroicon-s-` para solid
   - Ejemplos: `heroicon-o-users`, `heroicon-o-truck`, `heroicon-o-calendar`

5. **Widgets en recursos:**
   - Colocar en `getHeaderWidgets()` para aparecer arriba
   - Colocar en `getFooterWidgets()` para aparecer abajo

---

## Resumen

- **StatsOverviewWidget**: Para métricas y números
- **TableWidget**: Para listas y tablas de datos
- **CalendarWidget**: Para eventos en calendario
- **Dashboard**: Widgets generales en `app/Filament/Widgets/`
- **Recursos**: Widgets específicos en `app/Filament/Resources/Recurso/Widgets/`

