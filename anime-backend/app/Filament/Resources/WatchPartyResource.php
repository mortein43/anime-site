<?php

namespace AnimeSite\Filament\Resources;

use AnimeSite\Enums\WatchPartyStatus;
use AnimeSite\Filament\Resources\WatchPartyResource\Pages;
use AnimeSite\Filament\Resources\WatchPartyResource\RelationManagers\MessagesRelationManager;
use AnimeSite\Filament\Resources\WatchPartyResource\RelationManagers\ViewersRelationManager;
use AnimeSite\Models\Episode;
use AnimeSite\Models\WatchParty;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Str;

class WatchPartyResource extends Resource
{
    protected static ?string $model = WatchParty::class;
    protected static ?string $navigationIcon = 'heroicon-o-tv';
    protected static ?string $navigationGroup = 'Спільні кімнати';
    protected static ?string $pluralModelLabel = 'Спільні кімнати';
    protected static ?string $modelLabel = 'Спільна кімната';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Основна інформація')
                    ->schema([
                        TextInput::make('name')
                            ->label('Назва')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', WatchParty::generateSlug($state))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(WatchParty::class, 'slug', ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('user_id')
                            ->label('Хост')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('episode_id')
                            ->label('Епізод')
                            ->options(
                                Episode::query()
                                    ->join('animes', 'episodes.anime_id', '=', 'animes.id')
                                    ->selectRaw('episodes.id, CONCAT(animes.name, \' - Епізод \', episodes.number) as display_name')
                                    ->pluck('display_name', 'episodes.id')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                ->columns(2),
                Section::make('Налаштування кімнати')
                    ->schema([

                        TextInput::make('password')
                            ->label('Код')
                            ->maxLength(255)
                            ->visible(fn (Forms\Get $get) => $get('is_private')),
                        TextInput::make('max_viewers')
                            ->label('Максимальна кількість')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(10),
                        Select::make('watch_party_status')
                            ->label('Статус')
                            ->options(fn () => collect(WatchPartyStatus::cases())
                                ->mapWithKeys(fn (WatchPartyStatus $status) => [
                                    $status->value => $status->label(),
                                ])
                                ->toArray()
                            )
                            ->default(WatchPartyStatus::WAITING->value)
                            ->required(),
                        Toggle::make('is_private')
                            ->label('Приватна')
                            ->default(false),
                    ])
                    ->columns(3),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')
                    ->label('Хост')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('episode.anime.name')
                    ->label('Аніме')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('episode.number')
                    ->label('Епізод')
                    ->sortable(),
                TextColumn::make('watch_party_status')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn (WatchPartyStatus $state) => $state->label())
                    ->color(fn (WatchPartyStatus $state) => $state->color())
                    ->sortable(),
                TextColumn::make('activeViewersCount')
                    ->label('Кількість активних')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('max_viewers')
                    ->label('Максимальна кількість')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('started_at')
                    ->label('Почата в')
                    ->datetime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ended_at')
                    ->label('Закінчена в')
                    ->datetime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('watch_party_status')
                    ->options(WatchPartyStatus::class)
                    ->label('Статус'),
                SelectFilter::make('is_private')
                    ->label('Приватність')
                    ->options([
                        '1' => 'Приватна',
                        '0' => 'Публічна',
                    ])
                    ->label('Privacy'),
            ])
            ->actions([
                Action::make('start')
                    ->label('Почати')
                    -> Action(fn (WatchParty $record) => $record->start())
                    ->visible(fn (WatchParty $record) => $record->isWaiting())
                    ->requiresConfirmation()
                    ->color('success'),
                Action::make('end')
                    ->label('Закінчити')
                    ->action(fn (WatchParty $record) => $record->end())
                    ->visible(fn (WatchParty $record) => $record->isActive())
                    ->requiresConfirmation()
                    ->color('danger'),
                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ViewersRelationManager::class,
            MessagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWatchParties::route('/'),
            'create' => Pages\CreateWatchParty::route('/create'),
            'edit' => Pages\EditWatchParty::route('/{record}/edit'),
        ];
    }
}
