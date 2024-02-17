<?php

namespace App\Filament\Layouts;

use Filament\Forms\Components\Component;

class Column extends Component
{
    protected string $view = 'filament.layout.column';

    public static function make(): static
    {
        return app(static::class);
    }
}
