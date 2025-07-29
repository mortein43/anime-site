<?php

namespace AnimeSite\Filament\Resources\WatchPartyResource\RelationManagers;

use AnimeSite\Models\User;
use AnimeSite\Models\WatchParty;
use AnimeSite\Models\WatchPartyMessage;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    protected static ?string $recordTitleAttribute = 'message';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('message')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Автор')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('message')
                    ->label('Повідомлення')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(fn (WatchPartyMessage $record): string => $record->message),

                TextColumn::make('created_at')
                    ->label('Надіслано в')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form([
                        Select::make('watch_party_id')
                            ->label('Кімната')
                            ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->id)
                            ->disabled()
                            ->dehydrated()
                            ->options(WatchParty::query()->pluck('name', 'id'))
                            ->required(),

                        Select::make('user_id')
                            ->label('Автор')
                            ->options(
                                User::query()
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required(),

                        Textarea::make('message')
                            ->label('Повідомлення')
                            ->required()
                            ->maxLength(5000),

                        DateTimePicker::make('created_at')
                            ->label('Дата створення')
                            ->default(now())
                            ->required(),
                    ])
                    ->using(function (array $data, RelationManager $livewire): Model {
                        DB::table('watch_party_messages')->insert([
                            'id' => Str::ulid(), // генеруємо ULID для первинного ключа
                            'watch_party_id' => $data['watch_party_id'],
                            'user_id' => $data['user_id'],
                            'message' => $data['message'],
                            'created_at' => $data['created_at'],
                            'updated_at' => $data['created_at'],
                        ]);

                        // Повертаємо модель автора (User) або можна повертати якусь іншу модель
                        return User::find($data['user_id']);
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('10s'); // Refresh messages more frequently
    }
}
