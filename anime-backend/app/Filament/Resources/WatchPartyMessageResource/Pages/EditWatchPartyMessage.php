<?php

namespace AnimeSite\Filament\Resources\WatchPartyMessageResource\Pages;

use AnimeSite\Filament\Resources\WatchPartyMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWatchPartyMessage extends EditRecord
{
    protected static string $resource = WatchPartyMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
