<?php

namespace App\Filament\Resources\Maintenances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class MaintenancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Vehículo con placa y marca
                TextColumn::make('vehicle.plate')
                    ->label('Vehicle')
                    ->formatStateUsing(fn ($record) => $record && $record->vehicle 
                        ? "{$record->vehicle->plate} - {$record->vehicle->brand} {$record->vehicle->model}"
                        : 'N/A'
                    )
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('vehicle', function ($q) use ($search) {
                            $q->where('plate', 'like', "%{$search}%")
                              ->orWhere('brand', 'like', "%{$search}%")
                              ->orWhere('model', 'like', "%{$search}%");
                        });
                    })
                    ->sortable()
                    ->weight('medium'),
                
                // Tipo de mantenimiento con badge
                TextColumn::make('maintenanceType.name')
                    ->label('Maintenance Type')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->sortable()
                    ->searchable(),
                
                // Fecha de mantenimiento
                TextColumn::make('maintenance_date')
                    ->label('Maintenance Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-calendar'),
                
                // Kilometraje
                TextColumn::make('maintenance_mileage')
                    ->label('Mileage')
                    ->numeric()
                    ->suffix(' km')
                    ->sortable()
                    ->toggleable(),
                
                // Costo
                TextColumn::make('cost')
                    ->label('Cost')
                    ->money('USD')
                    ->sortable()
                    ->icon('heroicon-o-banknotes'),
                
                // Taller
                TextColumn::make('workshop')
                    ->label('Workshop')
                    ->searchable()
                    ->icon('heroicon-o-building-office')
                    ->toggleable(),
                
                // Próximo mantenimiento - fecha
                TextColumn::make('next_maintenance_date')
                    ->label('Next Maintenance')
                    ->state(function ($record) {
                        if (!$record->next_maintenance_date) {
                            return 'Not scheduled';
                        }
                        return Carbon::parse($record->next_maintenance_date)->format('d/m/Y');
                    })
                    ->badge()
                    ->color(function ($record) {
                        if (!$record->next_maintenance_date) {
                            return 'gray';
                        }
                        $daysUntil = Carbon::parse($record->next_maintenance_date)->diffInDays(now(), false);
                        if ($daysUntil <= 0) {
                            return 'danger'; // Vencido
                        }
                        if ($daysUntil <= 7) {
                            return 'danger'; // En 7 días o menos
                        }
                        if ($daysUntil <= 30) {
                            return 'warning'; // En 30 días o menos
                        }
                        return 'success';
                    })
                    ->icon(function ($record) {
                        if (!$record->next_maintenance_date) {
                            return 'heroicon-o-minus-circle';
                        }
                        $daysUntil = Carbon::parse($record->next_maintenance_date)->diffInDays(now(), false);
                        if ($daysUntil <= 30) {
                            return 'heroicon-o-exclamation-triangle';
                        }
                        return 'heroicon-o-calendar-days';
                    })
                    ->sortable()
                    ->toggleable(),
                
                // Próximo mantenimiento - kilometraje
                TextColumn::make('next_maintenance_mileage')
                    ->label('Next Mileage')
                    ->numeric()
                    ->suffix(' km')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                // Nota (preview)
                TextColumn::make('note')
                    ->label('Note')
                    ->limit(30)
                    ->tooltip(function ($record) {
                        return $record->note;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                
                // Fechas del sistema
                TextColumn::make('belongsTo')
                    ->label('Belongs To')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtro por vehículo
                SelectFilter::make('vehicle_id')
                    ->label('Vehicle')
                    ->relationship('vehicle', 'plate')
                    ->preload()
                    ->searchable()
                    ->multiple(),
                
                // Filtro por tipo de mantenimiento
                SelectFilter::make('maintenance_type_id')
                    ->label('Maintenance Type')
                    ->relationship('maintenanceType', 'name')
                    ->preload()
                    ->searchable()
                    ->multiple(),
                
                // Filtro por taller
                SelectFilter::make('workshop')
                    ->label('Workshop')
                    ->options(function () {
                        return \App\Models\Maintenance::query()
                            ->whereNotNull('workshop')
                            ->distinct()
                            ->orderBy('workshop')
                            ->pluck('workshop', 'workshop')
                            ->toArray();
                    })
                    ->searchable()
                    ->multiple(),
                
                // Filtro por rango de fechas de mantenimiento
                Filter::make('maintenance_date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('maintenance_from')
                            ->label('From Date'),
                        \Filament\Forms\Components\DatePicker::make('maintenance_until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['maintenance_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('maintenance_date', '>=', $date),
                            )
                            ->when(
                                $data['maintenance_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('maintenance_date', '<=', $date),
                            );
                    }),
                
                // Filtro por próximos mantenimientos
                Filter::make('upcoming_maintenance')
                    ->label('Upcoming Maintenance')
                    ->query(function (Builder $query): Builder {
                        return $query->whereNotNull('next_maintenance_date')
                            ->where('next_maintenance_date', '>=', now())
                            ->where('next_maintenance_date', '<=', now()->addDays(30));
                    }),
                
                // Filtro por mantenimientos vencidos
                Filter::make('overdue_maintenance')
                    ->label('Overdue Maintenance')
                    ->query(function (Builder $query): Builder {
                        return $query->whereNotNull('next_maintenance_date')
                            ->where('next_maintenance_date', '<', now());
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('maintenance_date', 'desc');
    }
}
