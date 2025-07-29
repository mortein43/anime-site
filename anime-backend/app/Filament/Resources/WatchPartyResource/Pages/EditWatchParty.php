<?php

namespace AnimeSite\Filament\Resources\WatchPartyResource\Pages;

use AnimeSite\Filament\Resources\WatchPartyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWatchParty extends EditRecord
{
    protected static string $resource = WatchPartyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
