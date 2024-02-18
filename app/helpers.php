<?php

namespace App;

use BackedEnumCase;
use Illuminate\Support\Str;

/**
 * @param BackedEnumCase[] $enum
 * @return string[]
 */
function enum_values(array $enum): array
{
    return collect($enum)
        ->map(fn ($state) => $state->value)
        ->toArray();
}

function is_secure(): bool
{
    return Str::startsWith(config('app.url'), 'https');
}
