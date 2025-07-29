<?php

namespace AnimeSite\Filament\Resources;

use AnimeSite\Filament\Resources\VoiceoverTeamResource\Pages;
use AnimeSite\Filament\Resources\VoiceoverTeamResource\RelationManagers;
use AnimeSite\Models\Studio;
use AnimeSite\Models\VoiceoverTeam;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VoiceoverTeamResource extends Resource
{
    protected static ?string $model = VoiceoverTeam::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $navigationGroup = 'Контент';
    protected static ?string $pluralModelLabel = 'Команди озвучення';
    protected static ?string $modelLabel = 'Команда озвучення';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        // Name Input
                        TextInput::make('name')
                            ->label('Назва')
                            ->required()
                            ->maxLength(128)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, string $state, Set $set) {
                                if ($operation == 'edit' || empty($state)) {
                                    return;
                                }
                                $set('slug', VoiceoverTeam::generateSlug($state));
                                $set('meta_title', VoiceoverTeam::makeMetaTitle($state));
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(128)
                            ->unique(ignoreRecord: true),
                    ])
                    ->columnSpan(2)
                    ->columns(2),

                // Image Section
                Section::make()
                    ->schema([
                        // Image Upload
                        FileUpload::make('image')
                            ->label('Зображення')
                            ->image()
                            ->disk('azure')
                            ->directory('voiceover_teams/images')
                            ->helperText('Завантажте зображення студії')
                            ->columnSpan(2),
                    ])
                    ->columnSpan(2),

                // Description Section
                Section::make()
                    ->schema([
                        // Description Input with RichEditor
                        RichEditor::make('description')
                            ->label('Опис')
                            ->maxLength(512)
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike',
                                'h2', 'h3', 'h4', 'bulletList', 'orderedList',
                                'link', 'blockquote', 'codeBlock', 'undo', 'redo',
                            ])
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, string $state, Set $set) {
                                if ($operation == 'edit' || empty($state)) {
                                    return;
                                }
                                $plainText = strip_tags($state);
                                $set('meta_description', VoiceoverTeam::makeMetaDescription($plainText));
                            }),
                    ])
                    ->columnSpan(3),

                // SEO Settings Section
                Section::make(__('SEO Налаштування'))
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        // Meta Title
                        TextInput::make('meta_title')
                            ->maxLength(128)
                            ->label(__('Meta заголовок')),

                        // Meta Description
                        TextInput::make('meta_description')
                            ->maxLength(376)
                            ->label(__('Meta опис')),

                        // Meta Image
                        FileUpload::make('meta_image')
                            ->image()
                            ->label(__('Meta зображення')),
                    ])
                    ->columnSpan(3)
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label(__('Опис'))
                    ->searchable()
                    ->limit(80),

                TextColumn::make('slug')
                    ->label(('Slug'))
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('image')
                    ->label('Зображення')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('meta_title')
                    ->label(('Meta загаловок'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('meta_description')
                    ->label(__('Meta опис'))
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('meta_image')
                    ->label('Meta зображення')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVoiceoverTeams::route('/'),
            'create' => Pages\CreateVoiceoverTeam::route('/create'),
            'edit' => Pages\EditVoiceoverTeam::route('/{record}/edit'),
        ];
    }
}
