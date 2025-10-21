<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Guava\Calendar\Enums\CalendarViewType;
 
class CalendarWidget extends Widget
{
    protected CalendarViewType $calendarView = CalendarViewType::ResourceTimeGridWeek;
    protected function getEvents(FetchInfo $info): Collection | array | Builder {}

    protected string $view = 'filament.widgets.calendar-widget';
}
