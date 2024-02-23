<?php

namespace App\Filament\Actions;

use App\Models\Documentation;
use App\Models\KBArticle;
use Filament\Actions\Action;
use Filament\Actions\MountableAction;

class KBArticlePreviewAction extends Action
{
    public static function setupKBArticlePreviewAction(MountableAction $action): void
    {
        $action->color('secondary');
        $action->icon('heroicon-o-eye');
        $action->link();
        $action->url(fn (KBArticle $record): string => $record->route(false));
        $action->openUrlInNewTab();
    }

    public static function getDefaultName(): ?string
    {
        return 'preview';
    }

    protected function setUp(): void
    {
        parent::setUp();
        static::setupKBArticlePreviewAction($this);
    }
}


