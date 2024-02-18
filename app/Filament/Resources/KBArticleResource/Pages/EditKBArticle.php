<?php

namespace App\Filament\Resources\KBArticleResource\Pages;

use App\Filament\Actions\KBArticlePreviewAction;
use App\Filament\Resources\KBArticleResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditKBArticle extends EditRecord
{
    protected static string $resource = KBArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            KBArticlePreviewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
