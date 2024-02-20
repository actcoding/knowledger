<?php

namespace App\Filament\Forms;

use App\Util\Status;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;
use function App\collect_enum_values;

class StatusSelect extends Select
{
    public static function color(string $state): string
    {
        return match ($state) {
            'active' => 'success',
            'draft' => 'info',
            default => 'neutral',
        };
    }

    protected function setUp(): void
    {
        parent::setUp();

        $status = collect_enum_values(Status::cases())
            ->mapWithKeys(function (string $item, int $key) {
                $value = Str::ucfirst($item);
                return [$item => "<span class=\"text-" . static::color($item) . "-600\">$value</span>"];
            })
            ->toArray();

        $this->color('info');
        $this->native(false);
        $this->allowHtml();
        $this->options($status);
    }
}
