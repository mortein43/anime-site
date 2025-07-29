<?php

namespace AnimeSite\Filament\Resources\WatchPartyResource\Pages;

use AnimeSite\Enums\WatchPartyStatus;
use AnimeSite\Filament\Resources\WatchPartyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

use Illuminate\Database\Eloquent\Builder;

class ListWatchParties extends ListRecords
{
    protected static string $resource = WatchPartyResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Всі'),
            'waiting' => Tab::make(WatchPartyStatus::WAITING->label())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('watch_party_status', WatchPartyStatus::WAITING->value)),
            'active' => Tab::make(WatchPartyStatus::ACTIVE->label())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('watch_party_status', WatchPartyStatus::ACTIVE->value)),
            'ended' => Tab::make(WatchPartyStatus::ENDED->label())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('watch_party_status', WatchPartyStatus::ENDED->value)),
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
