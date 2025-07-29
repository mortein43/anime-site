<?php

namespace AnimeSite\Filament\Resources\WatchPartyMessageResource\Pages;

use AnimeSite\Filament\Resources\WatchPartyMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWatchPartyMessages extends ListRecords
{
    protected static string $resource = WatchPartyMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
