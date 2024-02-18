<?php

namespace App\Filament\Widgets;

use App\Models\Documentation;
use App\Models\KBArticle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KBStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $countKb = Documentation::query()
            ->whereNull('deleted_at')
            ->count();
        $countArticle = KBArticle::query()
            ->whereNull('deleted_at')
            ->count();
        $countTrashed =
            (Documentation::query()
                ->withTrashed()
                ->whereNotNull('deleted_at')
                ->count())
            +
            (KBArticle::query()
                ->withTrashed()
                ->whereNotNull('deleted_at')
                ->count());

        return [
            Stat::make('Knowledge Bases', $countKb)
                ->icon('heroicon-o-book-open')
                ->url(route('filament.admin.resources.documentations.index')),
            Stat::make('Articles', $countArticle)
                ->icon('heroicon-o-document-text')
                ->url(route('filament.admin.resources.articles.index')),
            Stat::make('Trashed', $countTrashed)
                ->icon('heroicon-o-trash'),
        ];
    }
}
