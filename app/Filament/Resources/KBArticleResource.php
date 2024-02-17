<?php

namespace App\Filament\Resources;

use App\Filament\Layouts\Column;
use App\Filament\Resources\KBArticleResource\Pages;
use App\Models\KBArticle;
use BladeUI\Icons\Factory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KBArticleResource extends Resource
{
    protected static ?string $model = KBArticle::class;
    protected static ?string $modelLabel = 'Article';
    protected static ?string $pluralModelLabel = 'Articles';

    protected static ?string $slug = 'articles';
    protected static ?string $navigationGroup = 'Knowledge';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        /** @var Factory */
        $iconFactory = app(Factory::class);

        $icons = collect($iconFactory->all())
            ->keys()
            ->toArray();

        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Section::make('Info')
                            ->columnSpan(1)
                            ->description('General Information')
                            ->schema([
                                TextInput::make('title')
                                    ->required(),
                                TextInput::make('subtitle'),
                            ]),

                        Column::make()
                            ->schema([
                                FileUpload::make('header_image')
                                    ->image()
                                    ->helperText('A header image for this article.')
                                    ->directory('kb-article/header-image')
                                    ->visibility('private'),

                                Select::make('icon_mode')
                                    ->label('Icon Mode')
                                    ->native(false)
                                    ->options([
                                        'heroicon' => 'Hero Icons',
                                        'emoji' => 'Emoji',
                                        'custom' => 'Custom Image',
                                    ])
                                    ->live(),
                                IconPicker::make('icon')
                                    ->visible(fn (Get $get): bool => $get('icon_mode') == 'heroicon')
                                    ->label('Hero Icons')
                                    ->helperText(view('filament.form.helpers.icon_heroicons')),
                                TextInput::make('icon')
                                    ->visible(fn (Get $get): bool => $get('icon_mode') == 'emoji')
                                    ->label('Emoji')
                                    ->regex('/\p{Extended_Pictographic}/u'),
                                FileUpload::make('icon')
                                    ->visible(fn (Get $get): bool => $get('icon_mode') == 'custom')
                                    ->label('Custom Icon')
                                    ->helperText('')
                                    ->directory('kb-article/icon')
                                    ->visibility('private'),
                            ]),
                    ]),

                MarkdownEditor::make('content')
                    ->required()
                    ->helperText('Write the article content using the Markdown markup language.')
                    ->columnSpanFull()
                    ->fileAttachmentsDirectory('kb-article')
                    ->fileAttachmentsVisibility('private')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchable()
            ->columns([
                TextColumn::make('knowledgeBase.name')
                    ->label('Knowledge Base'),
                TextColumn::make('title')
                    ->description(fn (KBArticle $record): string => $record->subtitle),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'draft' => 'info',
                    }),
            ])
            ->filters([
                SelectFilter::make('knowledge_base')
                    ->relationship('knowledgeBase', 'name', fn (Builder $query) => $query->withTrashed())
                    ->searchable()
                    ->preload()
                    ->native(false),
                Tables\Filters\TrashedFilter::make(),
            ], FiltersLayout::AboveContent)
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
            'index' => Pages\ListKBArticles::route('/'),
            'create' => Pages\CreateKBArticle::route('/create'),
            'edit' => Pages\EditKBArticle::route('/{record}/edit'),
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
