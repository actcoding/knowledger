<?php

namespace App\Filament\Layouts;

use Filament\Forms\Components\Component;

class Wrapper extends Component
{
    protected string $view = 'filament.layout.wrapper';

    public static function make(): static
    {
        return app(static::class);
    }
}
