<?php

namespace App\Filament\Resources\Vehicles\Widgets;

use App\Models\Maintenance;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MantenimientosProximosVehiculos extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Maintenance::query()
                    ->with(['vehicle', 'maintenanceType'])
                    ->where('next_maintenance_date', '>=', now())
                    ->where('next_maintenance_date', '<=', now()->addDays(30))
                    ->orderBy('next_maintenance_date', 'asc')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('vehicle.plate')
                    ->label('Vehículo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('maintenanceType.name')
                    ->label('Tipo de Mantenimiento')
                    ->badge()
                    ->color('info'),
                TextColumn::make('next_maintenance_date')
                    ->label('Próximo Mantenimiento')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->color(function ($record) {
                        if (!$record->next_maintenance_date) return 'gray';
                        $daysUntil = now()->diffInDays($record->next_maintenance_date, false);
                        return $daysUntil <= 7 ? 'danger' : ($daysUntil <= 14 ? 'warning' : 'success');
                    }),
                TextColumn::make('next_maintenance_mileage')
                    ->label('Kilometraje')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cost')
                    ->label('Costo Estimado')
                    ->money('USD')
                    ->sortable(),
            ])
            ->heading('Próximos Mantenimientos')
            ->description('Mantenimientos programados en los próximos 30 días');
    }
}

