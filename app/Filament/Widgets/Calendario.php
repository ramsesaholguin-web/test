<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use \Guava\Calendar\Filament\CalendarWidget;

class Calendario extends Widget
{
    protected string $view = 'filament.widgets.calendario';
    protected function getEvents(FetchInfo $info): Collection | array | Builder {}
}
