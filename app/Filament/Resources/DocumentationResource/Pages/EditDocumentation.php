<?php

namespace App\Filament\Resources\DocumentationResource\Pages;

use App\Filament\Resources\DocumentationResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditDocumentation extends EditRecord
{
    protected static string $resource = DocumentationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->color('secondary')
                ->icon('heroicon-o-eye')
                ->link()
                ->url(route('kb.preview', [ 'slug' => $this->record->slug ]))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
