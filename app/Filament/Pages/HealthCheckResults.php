<?php

namespace App\Filament\Pages;

use ShuvroRoy\FilamentSpatieLaravelHealth\Pages\HealthCheckResults as BaseHealthCheckResults;

class HealthCheckResults extends BaseHealthCheckResults
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    public function getHeading(): string
    {
        return 'Health';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }
}
