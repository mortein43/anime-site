<?php

namespace AnimeSite\Filament\Resources\SearchHistoryResource\Pages;

use AnimeSite\Filament\Resources\SearchHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSearchHistories extends ListRecords
{
    protected static string $resource = SearchHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
