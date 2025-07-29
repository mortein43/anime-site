<?php

namespace AnimeSite\Filament\Resources;

use AnimeSite\Services\TmdbService;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use AnimeSite\Enums\ApiSourceName;
use AnimeSite\Enums\AttachmentType;
use AnimeSite\Enums\Country;
use AnimeSite\Enums\Kind;
use AnimeSite\Enums\Period;
use AnimeSite\Enums\RelatedType;
use AnimeSite\Enums\RestrictedRating;
use AnimeSite\Enums\Source;
use AnimeSite\Enums\Status;
use AnimeSite\Filament\Resources\AnimeResource\Pages;
use AnimeSite\Filament\Resources\AnimeResource\RelationManagers\CommentsRelationManager;
use AnimeSite\Filament\Resources\AnimeResource\RelationManagers\EpisodesRelationManager;
use AnimeSite\Filament\Resources\AnimeResource\RelationManagers\PeoplePivotRelationManager;
use AnimeSite\Filament\Resources\AnimeResource\RelationManagers\TagsRelationManager;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Studio;
use AnimeSite\ValueObjects\Attachment;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Notifications\Notification;

class AnimeResource extends Resource
{
    protected static ?string $model = Anime::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    protected static ?string $navigationGroup = 'Контент';
    protected static ?string $modelLabel = 'Аніме';
    protected static ?string $pluralModelLabel = 'Аніме';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Основна інформація')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Назва')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                                        if ($operation === 'edit' || empty($state)) {
                                            return;
                                        }
                                        $set('slug', Anime::generateSlug($state));
                                        $set('meta_title', Anime::makeMetaTitle($state));
                                    }),

                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(128)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('URL-дружній ідентифікатор'),

                                TagsInput::make('aliases')
                                    ->label('Псевдоніми')
                                    ->helperText('Альтернативні назви аніме'),

                                Toggle::make('is_published')
                                    ->label('Опубліковано')
                                    ->default(false)
                                    ->helperText('Чи відображається аніме на сайті'),
                            ])
                            ->columns(2),

                        Section::make('API інтеграція')
                            ->schema([
                                Repeater::make('api_sources')
                                    ->label('API джерела')
                                    ->schema([
                                        Select::make('source')
                                            ->label('Джерело')
                                            ->options(ApiSourceName::labels())
                                            ->default(ApiSourceName::TMDB->value)
                                            ->required(),

                                        TextInput::make('id')
                                            ->label('ID')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (?string $state, Set $set, Get $get, $livewire) {
                                                if ($get('source') === ApiSourceName::TMDB->value && !empty($state)) {
                                                    try {
                                                        $tmdb = new TmdbService();
                                                        $data = $tmdb->getAnimeById($state);

                                                        if (!$data) {
                                                            Notification::make()
                                                                ->title('Помилка')
                                                                ->body('Не вдалося отримати дані з TMDB')
                                                                ->danger()
                                                                ->send();
                                                            return;
                                                        }

                                                        $formattedData = $tmdb->formatDataForForm($data);
                                                        $livewire->form->fill($formattedData);

                                                        Notification::make()
                                                            ->title('Успіх')
                                                            ->body('Дані успішно завантажені з TMDB')
                                                            ->success()
                                                            ->send();
                                                    } catch (\Exception $e) {
                                                        Notification::make()
                                                            ->title('Помилка')
                                                            ->body('Помилка при завантаженні з TMDB: ' . $e->getMessage())
                                                            ->danger()
                                                            ->send();
                                                    }
                                                }
                                            })
                                            ->helperText('Введіть ID з обраного джерела'),
                                    ])
                                    ->columns(2)
                                    ->collapsible()
                                    ->defaultItems(0)
                                    ->addActionLabel('Додати API джерело'),
                            ]),
                    ])
                    ->columnSpan(2),

                Section::make('Опис')
                    ->schema([
                        RichEditor::make('description')
                            ->label('Опис')
                            ->maxLength(2048)
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike',
                                'h2', 'h3', 'h4', 'bulletList', 'orderedList',
                                'link', 'blockquote', 'codeBlock', 'undo', 'redo',
                            ])
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                                if ($operation === 'edit' || empty($state)) {
                                    return;
                                }
                                $plainText = strip_tags($state);
                                $set('meta_description', Anime::makeMetaDescription($plainText));
                            }),
                    ])
                    ->columnSpan(2),

                Section::make('Деталі аніме')
                    ->schema([
                        Select::make('studio_id')
                            ->label('Студія')
                            ->options(Studio::all()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('kind')
                            ->label('Тип')
                            ->options(Kind::options())
                            ->required(),

                        TextInput::make('imdb_score')
                            ->label('IMDB Рейтинг')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(10)
                            ->step(0.1)
                            ->required()
                            ->placeholder('7.5'),

                        Select::make('countries')
                            ->label('Країни')
                            ->multiple()
                            ->options(Country::toArray())
                            ->searchable()
                            ->preload(),

                        Select::make('status')
                            ->label('Статус')
                            ->options(Status::options())
                            ->required(),

                        Select::make('period')
                            ->label('Період')
                            ->options(Period::options()),

                        Select::make('restricted_rating')
                            ->label('Вікове обмеження')
                            ->options(RestrictedRating::options()),

                        Select::make('source')
                            ->label('Джерело адаптації')
                            ->options(Source::options())
                            ->required(),

                        TextInput::make('duration')
                            ->label('Тривалість (хвилини)')
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('24'),

                        TextInput::make('episodes_count')
                            ->label('Кількість епізодів')
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('12'),

                        DatePicker::make('first_air_date')
                            ->label('Дата початку показу')
                            ->displayFormat('d.m.Y'),

                        DatePicker::make('last_air_date')
                            ->label('Дата завершення показу')
                            ->displayFormat('d.m.Y'),
                    ])
                    ->columns(2)
                    ->columnSpan(2),

                Section::make('Постер')
                    ->schema([
                        FileUpload::make('poster')
                            ->label('Постер')
                            ->image()
                            ->disk('azure')
                            ->directory('animes/posters')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('2:3')
                            ->imageResizeTargetWidth('400')
                            ->imageResizeTargetHeight('600')
                            ->required()
                            ->helperText('Рекомендований розмір: 400x600px'),
                    ])
                    ->columnSpan(1),

                Section::make('Медіа файли')
                    ->schema([
                        Repeater::make('attachments')
                            ->label('Додаткові зображення')
                            ->schema([
                                Select::make('type')
                                    ->label('Тип')
                                    ->options(AttachmentType::labels())
                                    ->default(AttachmentType::PICTURE->value)
                                    ->required(),

                                FileUpload::make('src')
                                    ->label('Файл')
                                    ->image()
                                    ->required()
                                    ->disk('azure')
                                    ->directory('animes/attachments')
                                    ->imageResizeMode('cover')
                                    ->imageResizeTargetWidth('1920')
                                    ->imageResizeTargetHeight('1080'),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->addActionLabel('Додати зображення')
                            ->collapsible(),
                    ])
                    ->columnSpan(2),

                Section::make("Пов'язані аніме")
                    ->schema([
                        Repeater::make('related')
                            ->label("Пов'язані аніме")
                            ->schema([
                                Select::make('anime_id')
                                    ->label('Аніме')
                                    ->options(fn (Get $get) =>
                                    Anime::where('id', '!=', $get('../../id') ?? 0)
                                        ->pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('type')
                                    ->label('Тип зв\'язку')
                                    ->options(RelatedType::labels())
                                    ->default(RelatedType::SEASON->value)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Додати зв\'язок')
                            ->collapsible(),
                    ])
                    ->columnSpan(2),

                Section::make('Схожі аніме')
                    ->schema([
                        Repeater::make('similars')
                            ->label('Схожі аніме')
                            ->schema([
                                Select::make('anime_id')
                                    ->label('Аніме')
                                    ->options(fn (Get $get) =>
                                    Anime::where('id', '!=', $get('../../id') ?? 0)
                                        ->pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->addActionLabel('Додати схоже аніме')
                            ->collapsible(),
                    ])
                    ->columnSpan(2),

                Section::make('SEO налаштування')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta заголовок')
                            ->maxLength(60)
                            ->helperText('Рекомендовано до 60 символів'),

                        Textarea::make('meta_description')
                            ->label('Meta опис')
                            ->maxLength(160)
                            ->rows(3)
                            ->helperText('Рекомендовано до 160 символів'),

                        FileUpload::make('meta_image')
                            ->label('Meta зображення')
                            ->image()
                            ->disk('azure')
                            ->directory('animes/meta')
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('630')
                            ->helperText('Рекомендований розмір: 1200x630px'),
                    ])
                    ->columns(1)
                    ->columnSpan(2)
                    ->collapsible()
                    ->collapsed(),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                ImageColumn::make('poster')
                    ->label('Постер')
                    ->size(40)
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('kind')
                    ->label('Тип')
                    ->formatStateUsing(fn ($state) => $state?->name() ?? 'Не вказано')
                    ->badge()
                    ->color(fn ($state) => $state?->getBadgeColor() ?? 'gray')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Статус')
                    ->formatStateUsing(fn ($state) => $state?->name() ?? 'Не вказано')
                    ->badge()
                    ->color(fn ($state) => $state?->getBadgeColor() ?? 'gray')
                    ->sortable(),

                TextColumn::make('studio.name')
                    ->label('Студія')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('imdb_score')
                    ->label('IMDB')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) : 'Не оцінено')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('episodes_count')
                    ->label('Епізодів')
                    ->alignCenter()
                    ->toggleable(),

                BooleanColumn::make('is_published')
                    ->label('Опубліковано')
                    ->sortable(),

                TextColumn::make('first_air_date')
                    ->label('Дата виходу')
                    ->date('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Оновлено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Статус публікації')
                    ->placeholder('Всі')
                    ->trueLabel('Опубліковані')
                    ->falseLabel('Неопубліковані'),

                SelectFilter::make('studio')
                    ->label('Студія')
                    ->relationship('studio', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('kind')
                    ->label('Тип')
                    ->options(Kind::options()),

                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(Status::options()),

                SelectFilter::make('source')
                    ->label('Джерело')
                    ->options(Source::options()),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }

    public static function getRelations(): array
    {
        return [
            TagsRelationManager::class,
            PeoplePivotRelationManager::class,
            EpisodesRelationManager::class,
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnimes::route('/'),
            'create' => Pages\CreateAnime::route('/create'),
            'edit' => Pages\EditAnime::route('/{record}/edit'),
        ];
    }
}
