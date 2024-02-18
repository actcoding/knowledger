<?php

namespace App\Filament\Resources\DocumentationResource\Pages;

use App\Filament\Actions\KBPreviewAction;
use App\Filament\Resources\DocumentationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentation extends EditRecord
{
    protected static string $resource = DocumentationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            KBPreviewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
