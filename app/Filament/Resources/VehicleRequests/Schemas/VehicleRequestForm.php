<?php

namespace App\Filament\Resources\VehicleRequests\Schemas;

use App\Models\RequestStatus;
use App\Models\User;
use App\Models\Vehicle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Filament\Resources\Shared\Schemas\FormTemplate;
use Illuminate\Support\Carbon;

class VehicleRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Hidden fields for creation
                Hidden::make('user_id'),
                Hidden::make('id'), // Hidden ID field to detect if we're editing
                
                FormTemplate::groupWithSection([
                    FormTemplate::basicSection('Request details', [
                        DateTimePicker::make('requested_departure_date')
                            ->label('Departure Date & Time')
                            ->required()
                            ->minDate(now())
                            ->native(false)
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false)
                            ->reactive()
                            ->afterStateUpdated(function ($set, $state, $get) {
                                // Clear vehicle selection when dates change to force re-evaluation
                                $set('vehicle_id', null);
                                // Validate return date
                                if ($get('requested_return_date') && $state) {
                                    if (Carbon::parse($state)->gte(Carbon::parse($get('requested_return_date')))) {
                                        $set('requested_return_date', null);
                                    }
                                }
                            })
                            ->helperText('Select the departure date and time. Cannot be in the past.')
                            ->validationMessages([
                                'required' => 'The departure date is required.',
                                'after_or_equal' => 'The departure date cannot be in the past.',
                            ]),
                        
                        DateTimePicker::make('requested_return_date')
                            ->label('Return Date & Time')
                            ->required()
                            ->minDate(fn ($get) => $get('requested_departure_date') 
                                ? Carbon::parse($get('requested_departure_date'))->addMinute()
                                : now())
                            ->maxDate(fn ($get) => $get('requested_departure_date') 
                                ? Carbon::parse($get('requested_departure_date'))->addDays(90)
                                : now()->addDays(90))
                            ->native(false)
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false)
                            ->reactive()
                            ->afterStateUpdated(function ($set, $state, $get) {
                                // Clear vehicle selection when dates change to force re-evaluation
                                $set('vehicle_id', null);
                            })
                            ->helperText('Select the return date and time. Must be after departure date. Maximum 90 days from departure.')
                            ->validationMessages([
                                'required' => 'The return date is required.',
                                'after' => 'The return date must be after the departure date.',
                                'before_or_equal' => 'The return date cannot be more than 90 days after the departure date.',
                            ]),
                        
                        Select::make('vehicle_id')
                            ->label('Vehicle')
                            ->options(function ($get) {
                                $departureDate = $get('requested_departure_date');
                                $returnDate = $get('requested_return_date');
                                
                                // Get current request ID from the form state (if editing)
                                $currentRequestId = $get('_current_request_id') ?? null;
                                $currentVehicleId = $get('vehicle_id');
                                
                                // Get active status
                                $activeStatus = \App\Models\VehicleStatus::where('name', 'Active')->first();
                                
                                // If both dates are selected, filter by availability
                                if ($departureDate && $returnDate) {
                                    try {
                                        $departure = Carbon::parse($departureDate);
                                        $return = Carbon::parse($returnDate);
                                        
                                        // Use scope to filter available vehicles
                                        $vehicles = Vehicle::availableForDates($departure, $return, $currentRequestId)
                                            ->orderBy('plate')
                                            ->get();
                                    } catch (\Exception $e) {
                                        // If date parsing fails, fall back to active vehicles
                                        $vehicles = $activeStatus 
                                            ? Vehicle::where('status_id', $activeStatus->id)->orderBy('plate')->get()
                                            : collect();
                                    }
                                } else {
                                    // If dates not selected, show all active vehicles (but field will be disabled)
                                    $vehicles = $activeStatus 
                                        ? Vehicle::where('status_id', $activeStatus->id)
                                            ->orderBy('plate')
                                            ->get()
                                        : collect();
                                }
                                
                                // If editing and current vehicle is not in available list, include it
                                if ($currentVehicleId && $vehicles->isNotEmpty() && !$vehicles->contains('id', $currentVehicleId)) {
                                    $currentVehicle = Vehicle::find($currentVehicleId);
                                    if ($currentVehicle) {
                                        $vehicles->prepend($currentVehicle);
                                    }
                                }
                                
                                // Return empty array if no vehicles found
                                if ($vehicles->isEmpty()) {
                                    // Log for debugging (optional)
                                    \Log::debug('No vehicles available for dates', [
                                        'departure' => $departureDate,
                                        'return' => $returnDate,
                                        'active_status_id' => $activeStatus?->id,
                                    ]);
                                    return [];
                                }
                                
                                return $vehicles->mapWithKeys(function (Vehicle $vehicle) {
                                    $label = "{$vehicle->plate} - {$vehicle->brand} {$vehicle->model}";
                                    if ($vehicle->year) {
                                        $label .= " ({$vehicle->year})";
                                    }
                                    return [$vehicle->id => $label];
                                })->toArray();
                            })
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->disabled(fn ($get) => !$get('requested_departure_date') || !$get('requested_return_date'))
                            ->helperText(function ($get) {
                                $hasDates = $get('requested_departure_date') && $get('requested_return_date');
                                if (!$hasDates) {
                                    return 'Please select departure and return dates first to enable vehicle selection.';
                                }
                                
                                // Count available vehicles
                                try {
                                    $departure = Carbon::parse($get('requested_departure_date'));
                                    $return = Carbon::parse($get('requested_return_date'));
                                    $currentRequestId = $get('_current_request_id') ?? null;
                                    
                                    $availableCount = Vehicle::availableForDates($departure, $return, $currentRequestId)->count();
                                    
                                    if ($availableCount === 0) {
                                        return '⚠️ No vehicles are available for the selected dates. Please try different dates.';
                                    }
                                    
                                    return "✓ {$availableCount} vehicle(s) available for the selected dates.";
                                } catch (\Exception $e) {
                                    return 'Only vehicles available for the selected dates are shown.';
                                }
                            })
                            ->placeholder('Select a vehicle...')
                            ->validationMessages([
                                'required' => 'Please select a vehicle.',
                            ]),
                    ])->columns(2),
                ]),
                
                FormTemplate::basicSection('Trip Details', [
                    TextInput::make('destination')
                        ->label('Destination')
                        ->maxLength(255)
                        ->helperText('Where are you going?'),
                    
                    TextInput::make('event')
                        ->label('Event / Reason')
                        ->maxLength(255)
                        ->helperText('What is the purpose of this trip?'),
                ])->columns(2),
                
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->columnSpanFull()
                    ->helperText('Additional details about your request (optional)'),
                
                // Approval section - only visible when editing (not creating)
                // During creation, request_status_id will be set to "Pending" automatically in CreateVehicleRequest::mutateFormDataBeforeCreate
                FormTemplate::basicSection('Approval', [
                    Select::make('request_status_id')
                        ->label('Status')
                        ->relationship('requestStatus', 'name')
                        ->required(fn ($get) => !empty($get('id'))) // Only required when editing (visible)
                        ->default(fn () => RequestStatus::where('name', 'Pending')->first()?->id)
                        ->visible(fn ($get) => !empty($get('id'))), // Only show when editing
                    
                    DateTimePicker::make('approval_date')
                        ->label('Approval Date')
                        ->native(false)
                        ->displayFormat('d/m/Y H:i')
                        ->seconds(false)
                        ->visible(fn ($get) => !empty($get('id'))), // Only show when editing
                    
                    Select::make('approved_by')
                        ->label('Approved By')
                        ->relationship('approvedBy', 'name')
                        ->searchable()
                        ->preload()
                        ->visible(fn ($get) => !empty($get('id'))), // Only show when editing
                    
                    Textarea::make('approval_note')
                        ->label('Approval Note / Reason')
                        ->rows(2)
                        ->columnSpanFull()
                        ->helperText('Note from approver (required for rejection)')
                        ->visible(fn ($get) => !empty($get('id'))), // Only show when editing
                ])->columns(2)
                  ->visible(fn ($get) => !empty($get('id'))) // Only show when editing (ID exists)
                  ->collapsible(),
                
                // Hidden fields for creation
                Hidden::make('creation_date'),
                Hidden::make('belongsTo'),
            ]);
    }
}
