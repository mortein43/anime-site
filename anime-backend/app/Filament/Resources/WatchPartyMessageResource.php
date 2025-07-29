<?php

namespace AnimeSite\Filament\Resources;

use AnimeSite\Enums\WatchPartyStatus;
use AnimeSite\Filament\Resources\WatchPartyMessageResource\Pages;
use AnimeSite\Filament\Resources\WatchPartyMessageResource\RelationManagers;

use AnimeSite\Models\User;
use AnimeSite\Models\WatchParty;
use AnimeSite\Models\WatchPartyMessage;
use Cassandra\Type\Collection;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WatchPartyMessageResource extends Resource
{
    protected static ?string $model = WatchPartyMessage::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center';
    protected static ?string $navigationGroup = 'Спільні кімнати';
    protected static ?string $pluralModelLabel = 'Повідомлення в кімнатах';
    protected static ?string $modelLabel = 'Повідомлення в кімнаті';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                Select::make('watch_party_id')
                    ->label('Кімната')
                    ->relationship('watchParty', 'name')
                    ->options(
                        WatchParty::whereIn('watch_party_status', [WatchPartyStatus::ACTIVE, WatchPartyStatus::ENDED])
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('user_id', null); // Reset user_id when watch party changes
                    }),
                Select::make('user_id')
                    ->label('Відправник')
                    ->options(function (callable $get) {
                        $watchPartyId = $get('watch_party_id');
                        if (!$watchPartyId) {
                            return [];
                        }

                        $watchParty = WatchParty::find($watchPartyId);
                        if (!$watchParty) {
                            return [];
                        }

                        // Get host and viewers
                        $users = User::where('id', $watchParty->user_id)
                            ->orWhereIn('id', $watchParty->viewers()->pluck('user_id'))
                            ->pluck('name', 'id');

                        return $users;
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                Textarea::make('message')
                    ->label('Повідомлення')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                    ])
                ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('message')
                    ->label('Повідомлення')
                    ->searchable()
                    ->limit(45)
                    ->tooltip(fn ($state) => $state),
                TextColumn::make('user.name')
                    ->label('Відправник')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('watchParty.name')
                    ->label('Кімната')
                    ->searchable()
                    ->limit(45)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Відправлено в')
                    ->datetime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('watch_party_id')
                    ->relationship('watchParty', 'name')
                    ->label('Кімната')
                    ->searchable(),
                SelectFilter::make('watch_party_status')
                    ->options(WatchPartyStatus::class)
                    ->label('Статус кімнати')
                    ->query(fn (Builder $query, array $data) => $query->when(
                        $data['value'],
                        fn (Builder $query, $value) => $query->whereHas('watchParty', fn (Builder $q) => $q->where('watch_party_status', $value))
                    )),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers if needed (e.g., for comments or reactions)
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWatchPartyMessages::route('/'),
            'create' => Pages\CreateWatchPartyMessage::route('/create'),
            'edit' => Pages\EditWatchPartyMessage::route('/{record}/edit'),
        ];
    }
}
