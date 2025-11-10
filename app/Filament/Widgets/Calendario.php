<?php

namespace App\Filament\Widgets;

use App\Models\VehicleRequest;
use Carbon\WeekDay;
use Filament\Actions\Action;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class Calendario extends CalendarWidget
{
    protected string | HtmlString | null | bool $heading = 'Calendario de Solicitudes';
    
    // Habilitar clics en eventos
    protected bool $eventClickEnabled = true;
    
    // Configurar el primer día de la semana (Lunes)
    protected WeekDay $firstDay = WeekDay::Monday;

    public function getEvents(FetchInfo $info): Collection | array | Builder
    {
        return VehicleRequest::query()
            ->with(['user', 'vehicle', 'requestStatus'])
            ->where(function (Builder $query) use ($info) {
                // Obtener solicitudes que se solapen con el rango de fechas del calendario
                $query->whereBetween('requested_departure_date', [$info->start, $info->end])
                    ->orWhereBetween('requested_return_date', [$info->start, $info->end])
                    ->orWhere(function (Builder $q) use ($info) {
                        $q->where('requested_departure_date', '<=', $info->start)
                            ->where('requested_return_date', '>=', $info->end);
                    });
            })
            ->get()
            ->map(function (VehicleRequest $request) {
                return CalendarEvent::make()
                    ->key($request->id)
                    ->title($request->event ?? 'Solicitud #' . $request->id)
                    ->start($request->requested_departure_date)
                    ->end($request->requested_return_date)
                    ->backgroundColor($this->getStatusColor($request->requestStatus?->name))
                    ->extendedProps([
                        'model' => VehicleRequest::class,
                        'key' => $request->id,
                        'user' => $request->user?->name ?? 'N/A',
                        'vehicle' => $request->vehicle?->plate ?? 'N/A',
                        'destination' => $request->destination ?? 'N/A',
                        'status' => $request->requestStatus?->name ?? 'N/A',
                        'description' => $request->description,
                    ]);
            });
    }

    protected function getStatusColor(?string $status): string
    {
        return match ($status) {
            'Aprobado', 'Aprobada' => '#10b981', // Verde
            'Pendiente' => '#f59e0b', // Amarillo
            'Rechazado', 'Rechazada' => '#ef4444', // Rojo
            default => '#6b7280', // Gris
        };
    }

    /**
     * Manejar clic en evento
     */
    protected function onEventClick(EventClickInfo $info, Model $event, ?string $action = null): void
    {
        $this->mountAction('viewEvent');
    }

    /**
     * Acción para ver detalles del evento
     */
    protected function viewEventAction(): Action
    {
        $request = $this->getEventRecord();
        
        if (!$request) {
            $request = VehicleRequest::with(['user', 'vehicle', 'requestStatus'])->find($this->getRawCalendarContextData('event.id'));
        }
        
        return Action::make('viewEvent')
            ->label('Ver Detalles')
            ->modalHeading(fn () => $request ? 'Detalles de la Solicitud #' . $request->id : 'Detalles de la Solicitud')
            ->modalContent(view('filament.widgets.calendario-event-details', [
                'request' => $request,
            ]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Cerrar');
    }
    
    /**
     * Configurar el idioma del calendario
     */
    public function getLocale(): string
    {
        return 'es';
    }
}
