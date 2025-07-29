<?php

namespace AnimeSite\Filament\Resources;

use AnimeSite\Filament\Resources\SearchHistoryResource\Pages;
use AnimeSite\Filament\Resources\SearchHistoryResource\RelationManagers;
use AnimeSite\Models\SearchHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SearchHistoryResource extends Resource
{
    protected static ?string $model = SearchHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';
    protected static ?string $navigationGroup = 'Історії';
    protected static ?string $pluralModelLabel = 'Історії пошуків';
    protected static ?string $modelLabel = 'Історія пошуку';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('Користувач')
                ->relationship('user', 'name')
                ->required()
                ->searchable()
                ->preload(),

            Forms\Components\TextInput::make('query')
                ->label('Пошуковий запит')
                ->required()
                ->maxLength(248),
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
                TextColumn::make('query')
                    ->label('Запит')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
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
            'index' => Pages\ListSearchHistories::route('/'),
            'create' => Pages\CreateSearchHistory::route('/create'),
            'edit' => Pages\EditSearchHistory::route('/{record}/edit'),
        ];
    }
}
