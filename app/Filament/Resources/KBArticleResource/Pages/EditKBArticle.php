<?php

namespace App\Filament\Resources\KBArticleResource\Pages;

use App\Filament\Resources\KBArticleResource;
use App\Models\KBArticle;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditKBArticle extends EditRecord
{
    protected static string $resource = KBArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview-public')
                ->color('secondary')
                ->icon('heroicon-o-eye')
                ->label('Public View')
                ->link()
                ->url(fn (KBArticle $record): string => $record->route(true))
                ->openUrlInNewTab(),
            Action::make('preview-private')
                ->color('secondary')
                ->icon('heroicon-o-eye-slash')
                ->label('Preview')
                ->link()
                ->url(fn (KBArticle $record): string => $record->route(false))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
