<?php

namespace App\Filament\Resources\Vehicles\Widgets;

use App\Models\Maintenance;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MantenimientosRecientesVehiculos extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Maintenance::query()
                    ->with(['vehicle', 'maintenanceType'])
                    ->orderBy('maintenance_date', 'desc')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('vehicle.plate')
                    ->label('Vehículo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vehicle.brand')
                    ->label('Marca')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('vehicle.model')
                    ->label('Modelo')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('maintenanceType.name')
                    ->label('Tipo de Mantenimiento')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('maintenance_date')
                    ->label('Fecha de Mantenimiento')
                    ->dateTime('d/m/Y')
                    ->sortable(),
                TextColumn::make('maintenance_mileage')
                    ->label('Kilometraje')
                    ->numeric()
                    ->suffix(' km')
                    ->sortable(),
                TextColumn::make('cost')
                    ->label('Costo')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('workshop')
                    ->label('Taller')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('next_maintenance_date')
                    ->label('Próximo Mantenimiento')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->color(function ($record) {
                        if (!$record->next_maintenance_date) return 'gray';
                        $daysUntil = now()->diffInDays($record->next_maintenance_date, false);
                        return $daysUntil <= 7 ? 'danger' : ($daysUntil <= 14 ? 'warning' : 'success');
                    })
                    ->toggleable(),
            ])
            ->heading('Mantenimientos Recientes')
            ->description('Últimos 10 mantenimientos realizados');
    }
}

