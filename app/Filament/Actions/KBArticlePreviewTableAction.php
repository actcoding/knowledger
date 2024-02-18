<?php

namespace App\Filament\Actions;

use App\Models\Documentation;
use Filament\Actions\MountableAction;
use Filament\Tables\Actions\Action;

class KBArticlePreviewTableAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'preview';
    }

    protected function setUp(): void
    {
        parent::setUp();
        KBArticlePreviewAction::setupKBArticlePreviewAction($this);
    }
}


