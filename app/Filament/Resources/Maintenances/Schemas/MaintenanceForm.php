<?php

namespace App\Filament\Resources\Maintenances\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;
use App\Models\Vehicle;

class MaintenanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('Maintenance', [
                        Select::make('vehicle_id')
                            ->label('Vehicle')
                            ->options(function () {
                                return Vehicle::orderBy('plate')
                                    ->get()
                                    ->mapWithKeys(function (Vehicle $vehicle) {
                                        $label = "{$vehicle->plate} - {$vehicle->brand} {$vehicle->model}";
                                        if ($vehicle->year) {
                                            $label .= " ({$vehicle->year})";
                                        }
                                        return [$vehicle->id => $label];
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->required(),
                        Select::make('maintenance_type_id')
                            ->relationship('maintenanceType', 'name')
                            ->required(),
                        DateTimePicker::make('maintenance_date')
                            ->required(),
                        TextInput::make('maintenance_mileage')
                            ->required()
                            ->numeric(),
                        TextInput::make('cost')
                            ->numeric()
                            ->prefix('$'),
                        TextInput::make('workshop'),
                        Textarea::make('note')
                            ->columnSpanFull(),
                        TextInput::make('next_maintenance_mileage')
                            ->numeric(),
                        DateTimePicker::make('next_maintenance_date'),
                        FormTemplate::labeledText('belongsTo', 'Owner', true),
                    ])->columns(2),
                ])->columnSpanFull(),
            ]);
    }
}
