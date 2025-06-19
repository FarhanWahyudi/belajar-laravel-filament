<?php

namespace App\Filament\Widgets;

use App\Models\Faktur;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $faktur = Faktur::count();

        return [
            Stat::make('Faktur', $faktur),
            Stat::make('Bounce rate', '21%'),
            Stat::make('Average time on page', '3:12'),
        ];
    }
}
