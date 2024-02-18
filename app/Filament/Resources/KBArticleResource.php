<?php

namespace App\Filament\Resources;

use App\Filament\Actions\KBArticlePreviewTableAction;
use App\Filament\Actions\KBPreviewAction;
use App\Filament\Layouts\Column;
use App\Filament\Layouts\Wrapper;
use App\Filament\Resources\KBArticleResource\Pages;
use App\Models\Documentation;
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
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Section::make('Info')
                            ->columnSpan(1)
                            ->description('General Information')
                            ->schema([
                                Select::make('documentation_id')
                                    ->label('Knowledge Base')
                                    ->required()
                                    ->native(false)
                                    ->live(debounce: 500)
                                    ->searchable()
                                    ->preload()
                                    ->relationship(name: 'knowledgeBase', titleAttribute: 'name'),
                                TextInput::make('title')
                                    ->required()
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        $slug = $get('slug');
                                        if ($slug === null || str($slug)->length())
                                        $set('slug', str($state)->slug()->toString());
                                    }),
                                TextInput::make('subtitle'),

                                TextInput::make('slug')
                                    ->required()
                                    ->helperText('The sub-domain slug of this KB.')
                                    ->disabled(fn (Get $get) => $get('documentation_id') == null)
                                    ->prefix(function (Get $get) {
                                        $isSecure = Str::startsWith(config('app.url'), 'https');
                                        $protocol = $isSecure ? 'https://' : 'http://';
                                        $domain = config('knowledge-base.domain');
                                        $kb = $get('documentation_id');
                                        if ($kb == null)
                                        {
                                            return null;
                                        }

                                        $slug = Documentation::query()->where('id', '=', $kb)->first()->slug;
                                        return "$protocol$slug.$domain/article/";
                                    })
                                    ->alphaDash(),

                                TextInput::make('category')
                                    ->autocomplete()
                                    ->datalist(function (?KBArticle $record) {
                                        if ($record == null)
                                        {
                                            return [];
                                        }

                                        return KBArticle::query()
                                            ->where('documentation_id', '=', $record->documentation_id)
                                            ->select('category')
                                            ->distinct()
                                            ->get()
                                            ->pluck('category')
                                            ->all();
                                    }),
                            ]),

                        Column::make()
                            ->schema([
                                FileUpload::make('header_image')
                                    ->image()
                                    ->helperText('A header image for this article.')
                                    ->directory('kb-article/header-image')
                                    ->visibility('private'),

                                // FIXME: icon is empty when switching mode
                                Select::make('icon_mode')
                                    ->label('Icon Mode')
                                    ->native(false)
                                    ->options([
                                        null => 'None',
                                        'heroicon' => 'Hero Icons',
                                        'emoji' => 'Emoji',
                                        'custom' => 'Custom Image',
                                    ])
                                    ->live()
                                    ->afterStateUpdated(fn (Select $component) => $component
                                        ->getContainer()
                                        ->getComponent('icon_mode_fields')
                                        ->getChildComponentContainer()
                                        ->fill()
                                    ),
                                Wrapper::make()
                                    ->key('icon_mode_fields')
                                    ->schema(fn (Get $get): array => match($get('icon_mode')) {
                                        'heroicon' => [
                                            IconPicker::make('icon')
                                                ->label('Hero Icons')
                                                ->helperText(view('filament.form.helpers.icon_heroicons')),
                                        ],
                                        'emoji' => [
                                            TextInput::make('icon')
                                                ->label('Emoji')
                                                ->regex('/\p{Extended_Pictographic}/u'),
                                        ],
                                        'custom' => [
                                            FileUpload::make('icon')
                                                ->label('Custom Icon')
                                                ->helperText('Upload a custom image to use as the icon.')
                                                ->directory('kb-article/icon')
                                                ->visibility('private'),
                                        ],
                                        default => [],
                                    })
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
            ->striped()
            ->searchable()
            ->persistFiltersInSession()
            ->groups([
                'status'
            ])
            ->columns([
                TextColumn::make('knowledgeBase.name')
                    ->label('Knowledge Base')
                    ->hidden(function (HasTable $livewire) {
                        $state = $livewire->getTableFilterState('knowledge_base');
                        if ($state == null)
                        {
                            return false;
                        }

                        return str($state['value'])->length() > 0;
                    }),
                TextColumn::make('title')
                    ->description(fn (KBArticle $record): ?string => $record->subtitle),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'draft' => 'info',
                    }),
            ])
            ->filters([
                SelectFilter::make('knowledge_base')
                    ->label('Knowledge Base')
                    ->relationship('knowledgeBase', 'name', fn (Builder $query) => $query->withTrashed())
                    ->searchable()
                    ->preload()
                    ->native(false),
                Tables\Filters\TrashedFilter::make(),
            ], FiltersLayout::AboveContent)
            ->actions([
                KBArticlePreviewTableAction::make(),
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
