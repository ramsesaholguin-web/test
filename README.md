# Sistema de Gestión de Solicitudes de Vehículos

Sistema completo para la gestión de solicitudes de uso de vehículos, desarrollado con Laravel y Filament. Permite a los usuarios solicitar vehículos disponibles, seleccionando fechas y vehículos, con un proceso de aprobación/rechazo por parte de administradores.

---

## 📋 Resumen Ejecutivo

### Objetivo del Sistema
Crear un sistema donde los usuarios puedan solicitar el uso de vehículos, seleccionando fechas y vehículos disponibles, con un proceso de aprobación/rechazo por parte de administradores.

### Componentes Principales
1. **Interfaz de Usuario**: Formulario de solicitud con calendario y selector de vehículos
2. **Sistema de Validación**: Verificar disponibilidad de vehículos en fechas seleccionadas
3. **Panel de Administración**: Vista para aprobar/rechazar solicitudes
4. **Indicadores Visuales**: Mostrar disponibilidad de vehículos en tiempo real

### Flujo Principal
1. Usuario selecciona fechas
2. Sistema muestra vehículos disponibles
3. Usuario selecciona vehículo y completa datos
4. Sistema valida y crea solicitud con estado "Pendiente"
5. Administrador aprueba o rechaza
6. Sistema actualiza estado y notifica (opcional)

### Validaciones Críticas
1. Fechas válidas (retorno > salida, no pasadas)
2. Vehículo disponible en el rango de fechas
3. No solapamiento con solicitudes aprobadas
4. Estado del vehículo es "Disponible"

### Estados del Sistema
1. **Pendiente**: Estado inicial, esperando aprobación
2. **Aprobada**: Solicitud aprobada, vehículo ocupado
3. **Rechazada**: Solicitud rechazada, con motivo

---

## 🗄️ Estructura de Base de Datos

### Tablas Principales

#### 1. Tabla `vehicle_requests`
**Campos:**
- `id` - Identificador único
- `user_id` - Usuario que solicita (foreign key a users)
- `vehicle_id` - Vehículo solicitado (foreign key a vehicles)
- `requested_departure_date` - Fecha/hora de salida
- `requested_return_date` - Fecha/hora de retorno
- `description` - Descripción de la solicitud
- `destination` - Destino del viaje
- `event` - Evento o razón del viaje
- `request_status_id` - Estado de la solicitud (foreign key a request_statuses)
- `approval_date` - Fecha de aprobación/rechazo
- `approved_by` - Usuario que aprobó/rechazó (foreign key a users)
- `approval_note` - Nota de aprobación/rechazo
- `creation_date` - Fecha de creación
- `belongsTo` - Propietario/organización
- `created_at`, `updated_at` - Timestamps

**Estado:** ✅ La tabla ya existe con todos los campos necesarios

#### 2. Tabla `request_statuses`
**Campos:**
- `id` - Identificador único
- `name` - Nombre del estado

**Estados Necesarios:**
1. **Pendiente** - Solicitud creada, esperando aprobación
2. **Aprobada** - Solicitud aprobada por administrador
3. **Rechazada** - Solicitud rechazada por administrador
4. **(Opcional) Cancelada** - Solicitud cancelada por el usuario
5. **(Opcional) Completada** - Solicitud completada (vehículo devuelto)

**Estado:** ✅ La tabla existe, verificar que tenga los estados correctos

#### 3. Tabla `vehicles`
**Campos Relevantes:**
- `id` - Identificador único
- `plate` - Placa del vehículo
- `brand` - Marca
- `model` - Modelo
- `status_id` - Estado del vehículo (foreign key a vehicle_statuses)

**Estado:** ✅ La tabla existe

### Relaciones

#### Relaciones en VehicleRequest:
1. **user()** - BelongsTo User (quien solicita)
2. **vehicle()** - BelongsTo Vehicle (vehículo solicitado)
3. **requestStatus()** - BelongsTo RequestStatus (estado actual)
4. **approvedBy()** - BelongsTo User (quien aprobó/rechazó)

**Estado:** ✅ Todas las relaciones están definidas

---

## 🎨 Elementos de Interfaz

### 1. Pestaña/Navegación "Solicitudes"
- Ubicación: Menú de navegación principal
- Visibilidad: Usuarios autenticados
- Icono: `heroicon-o-document-text` o `heroicon-o-calendar`
- Contenido:
  - Lista de Solicitudes del Usuario
  - Botón "Nueva Solicitud"
  - Filtros por estado
  - Estadísticas: Widget con resumen de solicitudes

### 2. Modal/Ventana Emergente de Creación

**Estructura:**
- **Header**: Título "Nueva Solicitud de Vehículo" + Botón cerrar
- **Body**:
  - **Sección 1: Selección de Fechas**
    - Fecha y Hora de Salida (`requested_departure_date`)
    - Fecha y Hora de Retorno (`requested_return_date`)
    - Validaciones: Retorno > Salida, no fechas pasadas
  
  - **Sección 2: Selección de Vehículo**
    - Select con búsqueda o lista de tarjetas
    - Solo vehículos disponibles en el rango seleccionado
    - Indicadores visuales de disponibilidad
  
  - **Sección 3: Detalles del Viaje**
    - Destino (`destination`)
    - Evento/Razón (`event`)
    - Descripción (`description`) - Textarea

- **Footer**: Botón "Cancelar" + Botón "Enviar Solicitud"

### 3. Indicadores Visuales de Disponibilidad

**Estados:**
- **Disponible**: Verde, Check circle, Seleccionable
- **Ocupado**: Rojo, X circle, "Ocupado del [fecha] al [fecha]", No seleccionable
- **Mantenimiento**: Amarillo/Naranja, Wrench, "En Mantenimiento", No seleccionable
- **No Disponible**: Gris, Ban, "No Disponible", No seleccionable

### 4. Panel de Administración

**Vista de Lista:**
- Columnas: ID, Usuario, Vehículo, Fechas, Estado, Fecha de Creación, Acciones
- Filtros: Por estado, usuario, vehículo, rango de fechas
- Acciones Masivas: Aprobar/Rechazar múltiples solicitudes

**Modal de Aprobación/Rechazo:**
- **Aprobar**: Confirmación + Nota opcional
- **Rechazar**: Confirmación + Motivo requerido

---

## 🔄 Flujo de Usuario

### Flujo 1: Usuario Crea una Solicitud

```
1. Usuario → "Solicitudes" en el menú
2. Se muestra lista de sus solicitudes
3. Usuario → "Nueva Solicitud"
4. Se abre modal de creación
5. Usuario selecciona fecha de salida
6. Usuario selecciona fecha de retorno
7. Sistema filtra y muestra solo vehículos disponibles
8. Usuario selecciona vehículo disponible
9. Usuario completa destino, evento y descripción
10. Usuario → "Enviar Solicitud"
11. Sistema valida:
    - Fechas válidas (retorno > salida)
    - Vehículo disponible en esas fechas
    - Usuario autenticado
12. Si válido:
    - Crea solicitud con estado "Pendiente"
    - Asigna user_id del usuario actual
    - Guarda creation_date = ahora
    - Muestra mensaje de éxito
    - Cierra modal
    - Actualiza lista
13. Si inválido:
    - Muestra mensajes de error
    - Modal permanece abierto
    - Resalta campos con error
```

### Flujo 2: Administrador Aprueba/Rechaza

```
1. Administrador → "Solicitudes" (vista administrativa)
2. Se muestra lista de TODAS las solicitudes
3. Administrador filtra por "Pendientes" (opcional)
4. Administrador → solicitud pendiente
5. Se muestra vista de detalles
6. Administrador → "Aprobar" o "Rechazar"
7. Si "Aprobar":
    - Modal de confirmación
    - Nota opcional
    - Sistema actualiza: estado, approval_date, approved_by, approval_note
8. Si "Rechazar":
    - Modal de confirmación
    - Motivo requerido
    - Sistema actualiza: estado, approval_date, approved_by, approval_note
```

### Flujo 3: Usuario Ve el Estado de su Solicitud

```
1. Usuario → "Solicitudes"
2. Lista de sus solicitudes con:
    - Vehículo solicitado
    - Fechas
    - Estado (badge con color)
    - Fecha de creación
3. Usuario → solicitud
4. Vista de detalles con toda la información
```

---

## ✅ Validaciones y Restricciones

### Validaciones del Cliente (Frontend)

**Fechas:**
- Fecha de salida no puede ser en el pasado
- Fecha de retorno debe ser posterior a la de salida
- Rango máximo: 90 días (configurable)

**Vehículo:**
- Vehículo debe estar disponible
- Vehículo debe estar en estado "Disponible"
- No permitir vehículos en mantenimiento

**Campos:**
- Destino: Opcional pero recomendado
- Evento: Opcional
- Descripción: Opcional
- Usuario: Automático (usuario autenticado)

### Validaciones del Servidor (Backend)

**Lógica de Verificación de Disponibilidad:**
```
Un vehículo está disponible si:
1. No tiene solicitudes APROBADAS que se solapen con el rango de fechas
2. El estado del vehículo es "Disponible"
3. No está en mantenimiento en ese período
```

**Consulta de Verificación:**
```
Buscar solicitudes donde:
- vehicle_id = vehículo seleccionado
- request_status_id = "Aprobada"
- Y se cumple alguna de estas condiciones:
  * requested_departure_date está dentro del rango solicitado
  * requested_return_date está dentro del rango solicitado
  * El rango solicitado está completamente dentro de una solicitud existente
  * Una solicitud existente está completamente dentro del rango solicitado
```

**Casos de Solapamiento:**
1. **Solapamiento parcial inicio**: ❌ No disponible
2. **Solapamiento parcial fin**: ❌ No disponible
3. **Rango completamente dentro**: ❌ No disponible
4. **Rango completamente fuera**: ✅ Disponible
5. **Rango que contiene solicitud existente**: ❌ No disponible

**Validaciones Adicionales:**
- Usuario autenticado
- Vehículo existe y está activo
- Estado inicial: Siempre "Pendiente"
- User_id automático
- Creation_date automático

### Reglas de Negocio

1. Un usuario no puede tener múltiples solicitudes pendientes para el mismo vehículo en el mismo período
2. Un usuario no puede solicitar un vehículo para fechas ya pasadas
3. Un administrador no puede aprobar una solicitud si el vehículo ya está ocupado
4. Una solicitud aprobada no puede ser editada (solo cancelada o completada)
5. Una solicitud rechazada no puede ser editada (solo crear nueva)

---

## 🔐 Manejo de Estados

### Estados de Solicitud

#### 1. Pendiente (Estado Inicial)
- **Cuándo**: Al crear una nueva solicitud
- **Características**:
  - Puede ser editada por el usuario
  - Puede ser cancelada por el usuario
  - Puede ser aprobada o rechazada por administrador
  - No bloquea la disponibilidad del vehículo
- **Acciones**: Usuario (Editar, Cancelar, Ver) | Administrador (Aprobar, Rechazar, Ver, Editar)

#### 2. Aprobada
- **Cuándo**: Administrador aprueba la solicitud
- **Características**:
  - Bloquea la disponibilidad del vehículo
  - No puede ser editada (solo cancelada)
  - El vehículo aparece como "Ocupado"
  - Puede ser completada cuando se devuelve
- **Acciones**: Usuario (Ver, Cancelar) | Administrador (Ver, Cancelar, Marcar como Completada)

#### 3. Rechazada
- **Cuándo**: Administrador rechaza la solicitud
- **Características**:
  - No bloquea la disponibilidad del vehículo
  - No puede ser editada
  - Debe incluir un motivo (`approval_note`)
  - El usuario puede crear una nueva solicitud
- **Acciones**: Usuario (Ver) | Administrador (Ver)

#### 4. Cancelada (Opcional)
- **Cuándo**: Usuario cancela solicitud pendiente o administrador cancela aprobada
- **Características**:
  - Libera la disponibilidad del vehículo (si estaba aprobada)
  - Debe incluir razón de cancelación
  - No puede ser editada

#### 5. Completada (Opcional)
- **Cuándo**: Vehículo devuelto y registrado
- **Características**:
  - Libera la disponibilidad del vehículo
  - Registra fechas reales de uso
  - Cierra el ciclo de la solicitud

### Transiciones de Estado

```
Pendiente → Aprobada (por administrador)
Pendiente → Rechazada (por administrador)
Pendiente → Cancelada (por usuario)
Aprobada → Completada (por administrador/sistema)
Aprobada → Cancelada (por usuario/administrador)
```

### Lógica de Cambio de Estado

**Al Aprobar:**
1. Verificar que el vehículo sigue disponible
2. Si disponible: Actualizar estado, registrar approval_date, approved_by, approval_note
3. Si no disponible: Mostrar error, mantener estado "Pendiente"

**Al Rechazar:**
1. Requerir `approval_note` (motivo)
2. Actualizar estado a "Rechazada"
3. Registrar approval_date, approved_by, approval_note
4. (Opcional) Notificar al usuario

---

## 👥 Roles y Permisos

### Roles Necesarios

#### 1. Usuario Regular
**Permisos:**
- Ver sus propias solicitudes
- Crear nuevas solicitudes
- Editar sus solicitudes pendientes
- Cancelar sus solicitudes pendientes
- Ver detalles de sus solicitudes
- Ver estado de sus solicitudes

**Restricciones:**
- No puede ver solicitudes de otros usuarios
- No puede aprobar/rechazar solicitudes
- No puede editar solicitudes aprobadas/rechazadas

#### 2. Administrador
**Permisos:**
- Ver todas las solicitudes
- Crear solicitudes (para cualquier usuario)
- Editar cualquier solicitud
- Aprobar solicitudes
- Rechazar solicitudes
- Cancelar solicitudes
- Ver estadísticas de solicitudes
- Filtrar y buscar solicitudes

**Sin Restricciones:**
- Acceso completo al sistema de solicitudes

### Implementación de Permisos

**Nivel de Vista:**
- Mostrar/ocultar botones según rol
- Filtrar datos según rol (usuario solo ve las suyas)
- Mostrar secciones adicionales para administradores

**Nivel de Acción:**
- Validar permisos antes de ejecutar acciones
- Prevenir acciones no autorizadas
- Mostrar mensajes de error apropiados

**Nivel de Datos:**
- Filtrar consultas según rol
- Usuario: `where('user_id', auth()->id())`
- Administrador: Sin filtro (ver todas)

---

## 🔍 Consultas y Lógica de Negocio

### 1. Consulta de Vehículos Disponibles

**Parámetros:**
- `fecha_inicio`: Fecha/hora de salida solicitada
- `fecha_fin`: Fecha/hora de retorno solicitada
- `excluir_vehicle_id`: ID de vehículo a excluir (para edición)

**Lógica:**
```
1. Obtener todos los vehículos activos
2. Para cada vehículo, verificar:
   a. Estado del vehículo es "Disponible"
   b. No tiene mantenimientos programados en ese período
   c. No tiene solicitudes APROBADAS que se solapen
3. Retornar solo vehículos que cumplan todas las condiciones
```

**Consulta SQL Conceptual:**
```sql
SELECT vehicles.*
FROM vehicles
WHERE vehicles.status_id = 'Disponible'
  AND vehicles.id NOT IN (
    SELECT vehicle_id
    FROM vehicle_requests
    WHERE request_status_id = 'Aprobada'
      AND (
        (requested_departure_date BETWEEN ? AND ?)
        OR (requested_return_date BETWEEN ? AND ?)
        OR (requested_departure_date <= ? AND requested_return_date >= ?)
      )
  )
```

### 2. Verificación de Solapamiento

**Función Lógica:**
```
Dos rangos de fechas se solapan si:
- Inicio1 < Fin2 AND Fin1 > Inicio2
```

**Implementación:**
- Verificar en el servidor antes de guardar
- Verificar en el cliente para filtrar vehículos
- Verificar al aprobar (puede haber cambiado)

### 3. Filtrado de Solicitudes por Usuario

**Para Usuario Regular:**
```sql
WHERE user_id = usuario_autenticado
```

**Para Administrador:**
```sql
Sin filtro (ver todas)
O con filtros opcionales:
- Por usuario
- Por vehículo
- Por estado
- Por rango de fechas
```

---

## 📊 Estructura de Archivos y Componentes

### Estructura Recomendada

```
app/Filament/Resources/VehicleRequests/
├── VehicleRequestResource.php (Recurso principal)
├── Pages/
│   ├── ListVehicleRequests.php (Lista - con filtros por usuario)
│   ├── CreateVehicleRequest.php (Crear - modal o página)
│   ├── EditVehicleRequest.php (Editar)
│   └── ViewVehicleRequest.php (Ver detalles)
├── Schemas/
│   ├── VehicleRequestForm.php (Formulario)
│   └── VehicleRequestInfolist.php (Vista de detalles)
├── Tables/
│   └── VehicleRequestsTable.php (Tabla de lista)
└── Actions/
    ├── ApproveRequestAction.php (Acción de aprobar)
    └── RejectRequestAction.php (Acción de rechazar)
```

### Componentes de Interfaz

1. **Formulario de Solicitud**
   - DateTimePicker para fechas
   - Select con búsqueda para vehículos
   - TextInput para destino y evento
   - Textarea para descripción
   - Validaciones en tiempo real

2. **Selector de Vehículos**
   - Lista de vehículos disponibles
   - Tarjetas con información
   - Indicadores visuales
   - Filtrado automático por fechas

3. **Tabla de Solicitudes**
   - Columnas relevantes
   - Badges de estado
   - Acciones por fila
   - Filtros y búsqueda

4. **Modal de Aprobación/Rechazo**
   - Confirmación
   - Campo de nota (opcional para aprobar, requerido para rechazar)
   - Botones de acción

---

## 🚀 Plan de Implementación

### Fase 1: Configuración Básica
1. Verificar que la tabla `vehicle_requests` tiene todos los campos
2. Verificar que `request_statuses` tiene los estados correctos
3. Crear/verificar seeders para estados
4. Habilitar el recurso VehicleRequest en la navegación

### Fase 2: Formulario de Creación
1. Crear formulario con selección de fechas
2. Implementar selector de vehículos
3. Agregar validaciones de fechas
4. Implementar filtrado de vehículos disponibles

### Fase 3: Validaciones
1. Implementar verificación de disponibilidad en el servidor
2. Agregar validaciones de solapamiento
3. Validar estado del vehículo
4. Validar permisos de usuario

### Fase 4: Vista de Usuario
1. Crear vista de lista para usuarios
2. Filtrar solo solicitudes del usuario
3. Mostrar estado de cada solicitud
4. Permitir ver detalles

### Fase 5: Panel de Administración
1. Crear vista de lista para administradores
2. Implementar acciones de aprobar/rechazar
3. Agregar filtros y búsqueda
4. Mostrar estadísticas

### Fase 6: Mejoras de UX
1. Agregar indicadores visuales
2. Mejorar mensajes de feedback
3. Optimizar consultas
4. Agregar notificaciones (opcional)

---

## 🔧 Consideraciones Técnicas

### Performance

**Optimizaciones:**
- Índices en base de datos: En fechas y vehicle_id
- Eager loading: Cargar relaciones necesarias
- Cache: Cachear lista de vehículos disponibles (opcional)
- Paginación: Paginar lista de solicitudes

**Consultas Eficientes:**
- Usar `whereHas` con condiciones específicas
- Limitar resultados cuando sea posible
- Usar `select` para traer solo campos necesarios

### Seguridad

**Validaciones:**
- Validar permisos en cada acción
- Validar datos de entrada
- Prevenir SQL injection (Eloquent lo hace automáticamente)
- Prevenir XSS (Filament lo hace automáticamente)

**Autenticación:**
- Verificar usuario autenticado
- Verificar rol del usuario
- Verificar ownership (usuario solo ve sus solicitudes)

### Escalabilidad

**Futuras Mejoras:**
- Notificaciones por email
- Notificaciones en tiempo real
- Historial de cambios
- Reportes y estadísticas
- Integración con calendario externo
- API para aplicaciones móviles

---

## 📝 Consideraciones de UX/UI

### Indicadores Visuales

**En la Lista de Solicitudes:**
- **Badges de estado**: 
  - Pendiente: Amarillo/Naranja
  - Aprobada: Verde
  - Rechazada: Rojo
  - Cancelada: Gris

**En el Selector de Vehículos:**
- **Tarjetas de vehículos**: 
  - Disponible: Borde verde, icono check
  - Ocupado: Borde rojo, icono X, texto explicativo
  - Mantenimiento: Borde amarillo, icono wrench

### Mensajes y Notificaciones

**Mensajes de Éxito:**
- "Solicitud creada exitosamente"
- "Solicitud aprobada"
- "Solicitud rechazada"
- "Solicitud cancelada"

**Mensajes de Error:**
- "El vehículo no está disponible en ese período"
- "Las fechas seleccionadas no son válidas"
- "Debe proporcionar un motivo para rechazar"
- "No se pudo completar la acción"

**Mensajes Informativos:**
- "Verificando disponibilidad..."
- "Cargando vehículos disponibles..."
- "No hay vehículos disponibles en ese período. Intente con otras fechas."

### Responsive Design

**Mobile:**
- Modal a pantalla completa
- Calendario táctil
- Lista de vehículos scrollable
- Botones grandes y accesibles

**Desktop:**
- Modal centrado
- Calendario completo
- Grid de vehículos
- Acciones rápidas visibles

---

## 🛠️ Tecnologías Utilizadas

- **Laravel**: Framework PHP
- **Filament**: Panel de administración
- **SQLite**: Base de datos (desarrollo)
- **PHP**: Lenguaje de programación

---

## 📄 Licencia

Este proyecto está bajo la licencia MIT.

---

## 📚 Documentación Adicional

Para más detalles sobre la implementación, consultar:
- `docs/guia-implementacion-solicitudes.md` - Guía completa de implementación
- `docs/widgets-explicacion.md` - Documentación de widgets
- `docs/form-consistency-report.md` - Reporte de consistencia de formularios

---

**Última actualización**: Diciembre 2024
