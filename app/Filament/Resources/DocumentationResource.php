<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentationResource\Pages;
use App\Models\Documentation;
use App\Util\Colors;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class DocumentationResource extends Resource
{
    protected static ?string $model = Documentation::class;
    protected static ?string $modelLabel = 'Knowledge Base (KB)';
    protected static ?string $pluralModelLabel = 'Knowledge Bases';

    protected static ?string $navigationGroup = 'Knowledge';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        $isSecure = Str::startsWith(config('app.url'), 'https');

        $colors = collect(Colors::cases())
            ->map(fn ($state) => $state->value)
            ->sort()
            ->keyBy(fn (string $item) => $item)
            ->map(fn (string $item, string $key) => '<span class="text-' . $key . '-100">' . ucfirst($item) . '</span>');

        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Section::make('Brand')
                            ->columnSpan(1)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->helperText('The name of this KB.')
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        $slug = $get('slug');
                                        if ($slug === null || str($slug)->length())
                                        $set('slug', str($state)->slug()->toString());
                                    }),

                                TextInput::make('slug')
                                    ->required()
                                    ->helperText('The sub-domain slug of this KB.')
                                    ->prefix($isSecure ? 'https://' : 'http://')
                                    ->suffix('.' . config('knowledge-base.domain'))
                                    ->alphaDash(),

                                TextInput::make('password')
                                    ->password()
                                    ->revealable()
                                    ->autocomplete(false)
                                    ->helperText('Specify a password for this KB to prevent public access.')
                                    ->afterStateHydrated(function (TextInput $component, ?string $state) {
                                        $component->state('');
                                    }),

                                Select::make('theme_color')
                                    ->required()
                                    ->native(false)
                                    ->searchable()
                                    ->options($colors)
                                    ->allowHtml(),
                            ]),

                        FileUpload::make('logo')
                            ->image()
                            ->imagePreviewHeight('250')
                            ->helperText('The brand logo or icon of this KB.')
                            ->directory('knowledge-base/logo')
                            ->visibility('private'),
                    ]),

                Section::make('Additional Domains')
                    ->description('Associate additional domains this KB should be accessible from.')
                    ->schema([
                        Repeater::make('domains')
                            ->label('')
                            ->addActionLabel('Add another domain')
                            ->simple(
                                TextInput::make('domain')
                                    ->required()
                                    ->alphaDash()
                            )
                            ->reorderable(false)
                            ->cloneable()
                            ->defaultItems(0)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                ImageColumn::make('logo')
                    ->visibility('private'),
                TextColumn::make('name')
                    ->description(fn (Documentation $record): string => 'https://' . $record->slug . '.' . config('knowledge-base.domain')),
                TextColumn::make('domains')
                    ->label('Additional Domains')
                    ->default([[]])
                    ->badge()
                    ->listWithLineBreaks()
                    ->formatStateUsing(fn (array $state) => count($state)),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocumentations::route('/'),
            'create' => Pages\CreateDocumentation::route('/create'),
            'edit' => Pages\EditDocumentation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
