<?php

namespace AnimeSite\Filament\Resources\WatchHistoryResource\Pages;

use AnimeSite\Filament\Resources\WatchHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWatchHistory extends EditRecord
{
    protected static string $resource = WatchHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
