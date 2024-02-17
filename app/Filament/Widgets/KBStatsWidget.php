<?php

namespace App\Filament\Widgets;

use App\Models\Documentation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KBStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $count = Documentation::query()->count();

        return [
            Stat::make('Knowledge Bases', $count)
                ->icon('heroicon-o-book-open')
                ->url(route('filament.admin.resources.documentations.index')),
        ];
    }
}
