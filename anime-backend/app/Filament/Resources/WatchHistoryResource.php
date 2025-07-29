<?php
namespace AnimeSite\Filament\Resources;

use AnimeSite\Filament\Resources\WatchHistoryResource\Pages;
use AnimeSite\Models\Episode;
use AnimeSite\Models\User;
use AnimeSite\Models\WatchHistory;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WatchHistoryResource extends Resource
{
    protected static ?string $model = WatchHistory::class;

    protected static ?string $navigationGroup = 'Історії';
    protected static ?string $pluralModelLabel = 'Історії переглядів';
    protected static ?string $modelLabel = 'Історія перегляду';
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                Select::make('user_id')
                    ->label('Користувач')
                    ->relationship('user', 'name')
                    ->preload()
                    ->searchable()
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
                Section::make()
                    ->schema([
                TextInput::make('progress_time')
                    ->label('Прогрес (секунди)')
                    ->numeric()
                    ->minValue(0)
                    ->required(),

                DateTimePicker::make('created_at')
                    ->label('Створено')
                    ->required(),

               DateTimePicker::make('updated_at')
                    ->label('Оновлено')
                    ->required(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')
                    ->label('Користувач')
                    ->searchable(),
                TextColumn::make('episode.id')
                    ->label('Епізод')
                    ->sortable(),
                TextColumn::make('progress_time')
                    ->label('Прогрес (сек.)')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Оновлено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // можеш додати фільтри при потребі
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWatchHistories::route('/'),
            'create' => Pages\CreateWatchHistory::route('/create'),
            'edit' => Pages\EditWatchHistory::route('/{record}/edit'),
        ];
    }
}
