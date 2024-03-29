<?php

namespace App;

use BackedEnumCase;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @param BackedEnumCase[] $enum
 * @return Collection<string>
 */
function collect_enum_values(array $enum): Collection
{
    return collect($enum)->map(fn ($state) => $state->value);
}

/**
 * @param BackedEnumCase[] $enum
 * @return string[]
 */
function enum_values(array $enum): array
{
    return collect_enum_values($enum)->toArray();
}

function is_secure(): bool
{
    return Str::startsWith(config('app.url'), 'https');
}
