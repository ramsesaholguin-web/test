# Guía Práctica: Cómo Crear Widgets en Filament

## 📊 Tipo 1: StatsOverviewWidget (Widget de Estadísticas)

### ¿Qué hace?
Muestra tarjetas con números y estadísticas. Perfecto para métricas rápidas.

### Estructura Completa:

```php
<?php
namespace App\Filament\Widgets;

// 1. IMPORTAR LO NECESARIO
use App\Models\VehicleRequest;  // El modelo que vas a consultar
use Filament\Widgets\StatsOverviewWidget;  // Clase base
use Filament\Widgets\StatsOverviewWidget\Stat;  // Para crear cada estadística

// 2. CREAR LA CLASE
class EstadisticasSolicitudes extends StatsOverviewWidget
{
    // 3. OPCIONAL: Título del widget
    protected ?string $heading = 'Estadísticas de Solicitudes';
    
    // 4. MÉTODO OBLIGATORIO: Retorna un array de Stat
    protected function getStats(): array
    {
        // PASO 1: Obtener los datos usando Eloquent
        $totalSolicitudes = VehicleRequest::count();
        
        // PASO 2: Consultas más complejas (con relaciones)
        $solicitudesPendientes = VehicleRequest::whereHas('requestStatus', function ($query) {
            $query->where('name', 'like', '%pendiente%');
        })->count();
        
        // PASO 3: Retornar array de Stat::make()
        return [
            // Cada Stat::make() crea una tarjeta
            Stat::make('Total de Solicitudes', $totalSolicitudes)
                ->description('Todas las solicitudes registradas')  // Texto pequeño debajo
                ->descriptionIcon('heroicon-o-document-text')  // Icono pequeño
                ->color('primary')  // Color de la tarjeta
                ->icon('heroicon-o-document-text'),  // Icono grande
            
            Stat::make('Solicitudes Pendientes', $solicitudesPendientes)
                ->description('Esperando aprobación')
                ->color('warning')  // Amarillo/naranja
                ->icon('heroicon-o-clock'),
        ];
    }
}
```

### Desglose Línea por Línea:

**Línea 1-2: Namespace y imports**
```php
namespace App\Filament\Widgets;  // Ubicación del widget
use App\Models\VehicleRequest;  // Modelo que consultarás
use Filament\Widgets\StatsOverviewWidget;  // Clase base
```

**Línea 3: Extender la clase**
```php
class EstadisticasSolicitudes extends StatsOverviewWidget
```
- Debe extender `StatsOverviewWidget`
- El nombre puede ser cualquiera

**Línea 4: Método obligatorio**
```php
protected function getStats(): array
```
- **SIEMPRE** debe llamarse `getStats()`
- **SIEMPRE** debe retornar un `array`
- Este método se ejecuta cada vez que se carga el widget

**Línea 5: Obtener datos**
```php
$totalSolicitudes = VehicleRequest::count();
```
- Usa Eloquent normalmente
- `count()` cuenta registros
- Puedes usar `where()`, `whereHas()`, `with()`, etc.

**Línea 6: Consultas con relaciones**
```php
$solicitudesPendientes = VehicleRequest::whereHas('requestStatus', function ($query) {
    $query->where('name', 'like', '%pendiente%');
})->count();
```
- `whereHas('relacion')` filtra por relación
- `function ($query)` es un closure que modifica la consulta
- `$query->where()` filtra dentro de la relación

**Línea 7: Retornar estadísticas**
```php
return [
    Stat::make('Título', $valor)
        ->description('texto')
        ->color('primary')
        ->icon('heroicon-o-icono'),
];
```

### Métodos de Stat::make():

```php
Stat::make('Título', $valor)
    ->description('Texto descriptivo')           // Texto pequeño debajo del número
    ->descriptionIcon('heroicon-o-icono')       // Icono pequeño junto a la descripción
    ->color('primary|success|warning|danger|info')  // Color de la tarjeta
    ->icon('heroicon-o-icono-grande')           // Icono grande principal
    ->url('ruta-o-url')                         // Enlace al hacer clic
    ->chart([datos])                            // Gráfico (opcional)
```

---

## 📋 Tipo 2: TableWidget (Widget de Tabla)

### ¿Qué hace?
Muestra una tabla con datos. Perfecto para listas detalladas.

### Estructura Completa:

```php
<?php
namespace App\Filament\Widgets;

// 1. IMPORTAR
use App\Models\Vehicle;
use Filament\Tables\Columns\TextColumn;  // Tipo de columna
use Filament\Tables\Table;  // Para configurar la tabla
use Filament\Widgets\TableWidget as BaseWidget;  // Clase base

// 2. CREAR LA CLASE
class VehiculosMasUsados extends BaseWidget
{
    // 3. OPCIONAL: Orden de aparición (menor = primero)
    protected static ?int $sort = 2;
    
    // 4. OPCIONAL: Ancho del widget
    protected int | string | array $columnSpan = 'full';  // 'full' = ancho completo
    
    // 5. MÉTODO OBLIGATORIO: Configura la tabla
    public function table(Table $table): Table
    {
        return $table
            // PASO 1: Definir la consulta (query)
            ->query(
                Vehicle::query()
                    ->withCount('vehicleRequests')  // Contar relaciones
                    ->orderBy('vehicle_requests_count', 'desc')  // Ordenar
                    ->limit(5)  // Limitar resultados
            )
            // PASO 2: Definir las columnas
            ->columns([
                TextColumn::make('plate')
                    ->label('Placa')  // Etiqueta personalizada
                    ->searchable()    // Permite buscar
                    ->sortable(),     // Permite ordenar
                
                TextColumn::make('brand')
                    ->label('Marca')
                    ->searchable(),
                
                // Columna de relación
                TextColumn::make('status.name')
                    ->label('Estado')
                    ->badge()  // Muestra como badge
                    ->color('success'),  // Color del badge
            ])
            // PASO 3: Configuraciones opcionales
            ->heading('Vehículos Más Usados')
            ->description('Top 5 vehículos con más solicitudes');
    }
}
```

### Desglose Línea por Línea:

**Línea 1: Query (Consulta)**
```php
->query(
    Vehicle::query()
        ->withCount('vehicleRequests')  // Cuenta las solicitudes
        ->orderBy('vehicle_requests_count', 'desc')  // Ordena descendente
        ->limit(5)  // Solo 5 resultados
)
```
- Define QUÉ datos mostrar
- Usa Eloquent normalmente
- `withCount()` cuenta relaciones
- `orderBy()` ordena resultados
- `limit()` limita cantidad

**Línea 2: Columns (Columnas)**
```php
->columns([
    TextColumn::make('plate')  // Campo del modelo
        ->label('Placa')       // Nombre que se muestra
        ->searchable()         // Se puede buscar
        ->sortable(),          // Se puede ordenar
])
```

**Tipos de Columnas:**
- `TextColumn::make('campo')` - Texto simple
- `TextColumn::make('relacion.campo')` - Campo de relación
- `IconColumn::make('campo')` - Icono
- `ImageColumn::make('campo')` - Imagen
- `BooleanColumn::make('campo')` - Checkbox
- `BadgeColumn::make('campo')` - Badge

**Métodos comunes de columnas:**
```php
TextColumn::make('campo')
    ->label('Etiqueta')           // Nombre personalizado
    ->searchable()                // Permite buscar
    ->sortable()                  // Permite ordenar
    ->badge()                     // Muestra como badge
    ->color('primary')            // Color del badge/texto
    ->dateTime('d/m/Y')           // Formato de fecha
    ->money('USD')                // Formato de dinero
    ->numeric()                   // Formato numérico
    ->limit(50)                   // Limita caracteres
    ->wrap()                      // Permite texto largo
    ->url(fn($record) => 'ruta')  // Enlace dinámico
```

**Línea 3: Colores dinámicos**
```php
->color(fn ($record) => match($record->status?->name) {
    'Disponible' => 'success',
    'En Uso' => 'warning',
    'Mantenimiento' => 'danger',
    default => 'gray',
})
```
- `fn ($record)` es una arrow function
- `$record` es cada fila de la tabla
- `match()` es como un switch moderno
- Retorna el color según el valor

---

## 🗓️ Tipo 3: CalendarWidget (Widget de Calendario)

### ¿Qué hace?
Muestra eventos en un calendario visual.

### Estructura Completa:

```php
<?php
namespace App\Filament\Widgets;

use App\Models\VehicleRequest;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\FetchInfo;

class Calendario extends CalendarWidget
{
    // Título del calendario
    protected string | null $heading = 'Calendario de Solicitudes';
    
    // Habilitar clics en eventos
    protected bool $eventClickEnabled = true;
    
    // MÉTODO OBLIGATORIO: Obtiene los eventos
    public function getEvents(FetchInfo $info): Collection | array | Builder
    {
        return VehicleRequest::query()
            ->with(['user', 'vehicle'])
            // Filtrar por rango de fechas del calendario
            ->where(function ($query) use ($info) {
                $query->whereBetween('requested_departure_date', [$info->start, $info->end])
                      ->orWhereBetween('requested_return_date', [$info->start, $info->end]);
            })
            ->get()
            // Convertir cada solicitud en un evento del calendario
            ->map(function (VehicleRequest $request) {
                return CalendarEvent::make()
                    ->key($request->id)  // ID único
                    ->title($request->event ?? 'Solicitud #' . $request->id)
                    ->start($request->requested_departure_date)  // Fecha inicio
                    ->end($request->requested_return_date)  // Fecha fin
                    ->backgroundColor('#10b981')  // Color de fondo
                    ->extendedProps([  // Datos adicionales
                        'model' => VehicleRequest::class,
                        'key' => $request->id,
                    ]);
            });
    }
}
```

---

## 📍 Dónde Colocar los Widgets

### Dashboard (Inicio)
**Ubicación:** `app/Filament/Widgets/MiWidget.php`

**Se descubren automáticamente:**
```php
// En AdminPanelProvider.php
->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
```

**Aparecen automáticamente en el dashboard.**

### Recursos (Páginas específicas)
**Ubicación:** `app/Filament/Resources/Users/Widgets/MiWidget.php`

**Se registran manualmente:**
```php
// En ListUsers.php
protected function getHeaderWidgets(): array
{
    return [
        UsuariosStats::class,
    ];
}
```

**Aparecen solo en esa página de recurso.**

---

## 🔄 Flujo de Trabajo Paso a Paso

### Para crear un Widget de Estadísticas:

1. **Crear el archivo:**
   ```
   app/Filament/Widgets/MiWidget.php
   ```

2. **Estructura básica:**
   ```php
   <?php
   namespace App\Filament\Widgets;
   
   use App\Models\MiModelo;
   use Filament\Widgets\StatsOverviewWidget;
   use Filament\Widgets\StatsOverviewWidget\Stat;
   
   class MiWidget extends StatsOverviewWidget
   {
       protected function getStats(): array
       {
           // Aquí va tu código
       }
   }
   ```

3. **Obtener datos:**
   ```php
   $total = MiModelo::count();
   $activos = MiModelo::where('activo', true)->count();
   ```

4. **Retornar estadísticas:**
   ```php
   return [
       Stat::make('Total', $total)
           ->color('primary')
           ->icon('heroicon-o-icono'),
   ];
   ```

### Para crear un Widget de Tabla:

1. **Crear el archivo:**
   ```
   app/Filament/Widgets/MiTablaWidget.php
   ```

2. **Estructura básica:**
   ```php
   <?php
   namespace App\Filament\Widgets;
   
   use App\Models\MiModelo;
   use Filament\Tables\Columns\TextColumn;
   use Filament\Tables\Table;
   use Filament\Widgets\TableWidget as BaseWidget;
   
   class MiTablaWidget extends BaseWidget
   {
       public function table(Table $table): Table
       {
           return $table
               ->query(MiModelo::query())
               ->columns([
                   TextColumn::make('campo')
                       ->label('Etiqueta'),
               ]);
       }
   }
   ```

---

## 💡 Ejemplos Prácticos

### Ejemplo 1: Widget Simple de Conteo
```php
protected function getStats(): array
{
    return [
        Stat::make('Total Usuarios', User::count())
            ->description('Registrados')
            ->color('primary')
            ->icon('heroicon-o-users'),
    ];
}
```

### Ejemplo 2: Widget con Filtros
```php
protected function getStats(): array
{
    $hoy = VehicleRequest::whereDate('creation_date', today())->count();
    $esteMes = VehicleRequest::whereMonth('creation_date', now()->month)->count();
    
    return [
        Stat::make('Hoy', $hoy)->color('success'),
        Stat::make('Este Mes', $esteMes)->color('info'),
    ];
}
```

### Ejemplo 3: Widget con Relaciones
```php
protected function getStats(): array
{
    $pendientes = VehicleRequest::whereHas('requestStatus', function ($q) {
        $q->where('name', 'like', '%pendiente%');
    })->count();
    
    return [
        Stat::make('Pendientes', $pendientes)
            ->color('warning')
            ->icon('heroicon-o-clock'),
    ];
}
```

### Ejemplo 4: Tabla con Colores Dinámicos
```php
->columns([
    TextColumn::make('status.name')
        ->badge()
        ->color(function ($record) {
            return match($record->status?->name) {
                'Activo' => 'success',
                'Inactivo' => 'danger',
                default => 'gray',
            };
        }),
])
```

---

## 🎨 Colores Disponibles

- `primary` - Azul (información general)
- `success` - Verde (éxito/positivo)
- `warning` - Amarillo/Naranja (advertencia)
- `danger` - Rojo (error/crítico)
- `info` - Azul claro (información)
- `gray` - Gris (neutral)

---

## 🔍 Consultas Eloquent Comunes

```php
// Contar todos
Model::count()

// Contar con condición
Model::where('campo', 'valor')->count()

// Contar con relación
Model::whereHas('relacion', fn($q) => $q->where('campo', 'valor'))->count()

// Contar relaciones
Model::withCount('relacion')->get()

// Filtrar por fecha
Model::whereDate('fecha', today())->count()
Model::whereMonth('fecha', now()->month)->count()
Model::whereYear('fecha', now()->year)->count()

// Últimos X minutos
Model::where('updated_at', '>=', now()->subMinutes(15))->count()
```

---

## ✅ Checklist para Crear un Widget

- [ ] Decidir el tipo (StatsOverviewWidget o TableWidget)
- [ ] Crear archivo en la ubicación correcta
- [ ] Importar las clases necesarias
- [ ] Extender la clase base correcta
- [ ] Implementar el método obligatorio
- [ ] Obtener datos con Eloquent
- [ ] Configurar y retornar
- [ ] Probar en el dashboard/recurso

---

## 🚀 Resumen Rápido

**StatsOverviewWidget:**
- Método: `getStats()`
- Retorna: `array` de `Stat::make()`
- Uso: Métricas y números

**TableWidget:**
- Método: `table()`
- Retorna: `Table` configurado
- Uso: Listas y tablas

**CalendarWidget:**
- Método: `getEvents()`
- Retorna: `Collection` de `CalendarEvent`
- Uso: Eventos en calendario

