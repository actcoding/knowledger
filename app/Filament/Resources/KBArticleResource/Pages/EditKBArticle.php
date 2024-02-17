<?php

namespace App\Filament\Resources\KBArticleResource\Pages;

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
            Action::make('preview')
                ->color('secondary')
                ->icon('heroicon-o-eye')
                ->link()
                ->url(route('kb.preview.article', [ 'slug' => $this->record->knowledgeBase->slug, 'article' => $this->record->slug ]))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
