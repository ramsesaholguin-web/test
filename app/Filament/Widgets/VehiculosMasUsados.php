<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class VehiculosMasUsados extends BaseWidget
{
    protected static ?int $sort = 7;
    
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        // Visible para todos los usuarios (administradores y usuarios regulares)
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Vehicle::query()
                    ->withCount('vehicleRequests')
                    ->orderBy('vehicle_requests_count', 'desc')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('plate')
                    ->label('Placa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('brand')
                    ->label('Marca')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('model')
                    ->label('Modelo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vehicle_requests_count')
                    ->label('Solicitudes')
                    ->counts('vehicleRequests')
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('status.name')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($record) => match($record->status?->name) {
                        'Disponible' => 'success',
                        'En Uso' => 'warning',
                        'Mantenimiento' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->heading('Vehículos Más Usados')
            ->description('Top 5 vehículos con más solicitudes');
    }
}

