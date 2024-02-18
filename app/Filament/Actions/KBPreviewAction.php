<?php

namespace App\Filament\Actions;

use App\Models\Documentation;
use Filament\Actions\Action;
use Filament\Actions\MountableAction;

class KBPreviewAction extends Action
{
    public static function setupKBPreviewAction(MountableAction $action): void
    {
        $action->color('secondary');
        $action->icon('heroicon-o-eye');
        $action->link();
        $action->url(fn (Documentation $record): string => route('kb.preview', [ 'slug' => $record->slug ]));
        $action->openUrlInNewTab();
    }

    public static function getDefaultName(): ?string
    {
        return 'preview';
    }

    protected function setUp(): void
    {
        parent::setUp();
        static::setupKBPreviewAction($this);
    }
}


