<?php

namespace App\Filament\Resources\KBArticleResource\Pages;

use App\Filament\Resources\KBArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKBArticles extends ListRecords
{
    protected static string $resource = KBArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
