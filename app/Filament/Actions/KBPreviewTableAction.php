<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\Action;

class KBPreviewTableAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'preview';
    }

    protected function setUp(): void
    {
        parent::setUp();
        KBPreviewAction::setupKBPreviewAction($this);
    }
}
