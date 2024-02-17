<?php

namespace App;

use BackedEnumCase;

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
