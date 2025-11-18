<?php

namespace App\Filament\Resources\VehicleDocuments\Tables;

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

class VehicleDocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Vehículo con placa
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
                
                // Nombre del documento
                TextColumn::make('document_name')
                    ->label('Document Name')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-document'),
                
                // Fecha de expiración con indicador visual
                TextColumn::make('expiration_date')
                    ->label('Expiration Date')
                    ->date('d/m/Y')
                    ->sortable()
                    ->badge()
                    ->color(function ($record) {
                        if (!$record->expiration_date) {
                            return 'gray';
                        }
                        $daysUntil = Carbon::parse($record->expiration_date)->diffInDays(now(), false);
                        if ($daysUntil < 0) {
                            return 'danger'; // Vencido
                        }
                        if ($daysUntil <= 30) {
                            return 'danger'; // En 30 días o menos
                        }
                        if ($daysUntil <= 60) {
                            return 'warning'; // En 60 días o menos
                        }
                        return 'success';
                    })
                    ->icon(function ($record) {
                        if (!$record->expiration_date) {
                            return 'heroicon-o-minus-circle';
                        }
                        $daysUntil = Carbon::parse($record->expiration_date)->diffInDays(now(), false);
                        if ($daysUntil <= 60) {
                            return 'heroicon-o-exclamation-triangle';
                        }
                        return 'heroicon-o-calendar-days';
                    }),
                
                // Estado de vencimiento
                TextColumn::make('expiration_status')
                    ->label('Status')
                    ->state(function ($record) {
                        if (!$record->expiration_date) {
                            return 'No expiration';
                        }
                        $daysUntil = Carbon::parse($record->expiration_date)->diffInDays(now(), false);
                        if ($daysUntil < 0) {
                            return abs($daysUntil) . ' days overdue';
                        }
                        if ($daysUntil <= 30) {
                            return 'Expires in ' . $daysUntil . ' days';
                        }
                        if ($daysUntil <= 60) {
                            return 'Expires in ' . $daysUntil . ' days';
                        }
                        return 'Valid';
                    })
                    ->badge()
                    ->color(function ($record) {
                        if (!$record->expiration_date) {
                            return 'gray';
                        }
                        $daysUntil = Carbon::parse($record->expiration_date)->diffInDays(now(), false);
                        if ($daysUntil < 0) {
                            return 'danger';
                        }
                        if ($daysUntil <= 30) {
                            return 'danger';
                        }
                        if ($daysUntil <= 60) {
                            return 'warning';
                        }
                        return 'success';
                    })
                    ->icon(function ($record) {
                        if (!$record->expiration_date) {
                            return 'heroicon-o-minus-circle';
                        }
                        $daysUntil = Carbon::parse($record->expiration_date)->diffInDays(now(), false);
                        if ($daysUntil <= 60) {
                            return 'heroicon-o-exclamation-triangle';
                        }
                        return 'heroicon-o-check-circle';
                    }),
                
                // Ruta del archivo (truncada)
                TextColumn::make('file_path')
                    ->label('File')
                    ->limit(30)
                    ->tooltip(function ($record) {
                        return $record->file_path;
                    })
                    ->icon('heroicon-o-paper-clip')
                    ->toggleable(),
                
                // Fecha de subida
                TextColumn::make('upload_date')
                    ->label('Upload Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->toggleable(),
                
                // Usuario que subió
                TextColumn::make('uploadedBy.name')
                    ->label('Uploaded By')
                    ->icon('heroicon-o-user')
                    ->sortable()
                    ->searchable()
                    ->placeholder('Unknown')
                    ->toggleable(),
                
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
                
                // Filtro por documentos próximos a vencer
                Filter::make('expiring_soon')
                    ->label('Expiring Soon')
                    ->query(function (Builder $query): Builder {
                        return $query->whereNotNull('expiration_date')
                            ->where('expiration_date', '>=', now())
                            ->where('expiration_date', '<=', now()->addDays(30));
                    }),
                
                // Filtro por documentos vencidos
                Filter::make('expired')
                    ->label('Expired')
                    ->query(function (Builder $query): Builder {
                        return $query->whereNotNull('expiration_date')
                            ->where('expiration_date', '<', now());
                    }),
                
                // Filtro por documentos sin fecha de expiración
                Filter::make('no_expiration')
                    ->label('No Expiration Date')
                    ->query(function (Builder $query): Builder {
                        return $query->whereNull('expiration_date');
                    }),
                
                // Filtro por rango de fechas de expiración
                Filter::make('expiration_date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('expiration_from')
                            ->label('From Date'),
                        \Filament\Forms\Components\DatePicker::make('expiration_until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['expiration_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('expiration_date', '>=', $date),
                            )
                            ->when(
                                $data['expiration_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('expiration_date', '<=', $date),
                            );
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
            ->defaultSort('expiration_date', 'asc');
    }
}
