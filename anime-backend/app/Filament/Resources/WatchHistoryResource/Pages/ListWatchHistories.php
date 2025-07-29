<?php

namespace AnimeSite\Filament\Resources\WatchHistoryResource\Pages;

use AnimeSite\Filament\Resources\WatchHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWatchHistories extends ListRecords
{
    protected static string $resource = WatchHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
