<?php

namespace AnimeSite\Filament\Resources\WatchPartyResource\RelationManagers;

use AnimeSite\Models\User;
use AnimeSite\Models\WatchParty;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class ViewersRelationManager extends RelationManager
{
    protected static string $relationship = 'viewers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Viewer')
                    ->relationship('viewers', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DateTimePicker::make('pivot.joined_at')
                    ->label('Joined At')
                    ->required()
                    ->default(now()),
                DateTimePicker::make('pivot.left_at')
                    ->label('Left At')
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ім\'я')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pivot.joined_at')
                    ->label('Доєднався в')
                    ->datetime()
                    ->sortable(),
                TextColumn::make('pivot.left_at')
                    ->label('Вийшов в')
                    ->datetime()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->getStateUsing(fn ($record) => is_null($record->pivot->left_at) ? 'Активний' : 'Вийшов')
                    ->badge()
                    ->color(fn ($state) => $state === 'Активний' ? 'success' : 'gray'),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->query(fn (Builder $query) => $query->whereNull('watch_party_user.left_at'))
                    ->label('Active Viewers'),
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
                            ->label('Глядач')
                            ->options(
                                User::query()
                                    ->whereNotIn('id', $this->getOwnerRecord()->viewers()->pluck('user_id'))
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required(),
                        DateTimePicker::make('joined_at')
                            ->label('Доєднався в')
                            ->required()
                            ->default(now()),
                        DateTimePicker::make('left_at')
                            ->label('Вийшов в')
                            ->nullable(),
                    ])
                    ->using(function (array $data, RelationManager $livewire): Model {
                        DB::table('watch_party_user')->insert([
                            'user_id' => $data['user_id'],
                            'watch_party_id' => $data['watch_party_id'],
                            'joined_at' => $data['joined_at'],
                            'left_at' => $data['left_at'] ?? null,
                        ]);

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
            ]);
    }
}
