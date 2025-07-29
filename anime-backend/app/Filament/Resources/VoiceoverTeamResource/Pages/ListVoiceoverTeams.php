<?php

namespace AnimeSite\Filament\Resources\VoiceoverTeamResource\Pages;

use AnimeSite\Filament\Resources\VoiceoverTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVoiceoverTeams extends ListRecords
{
    protected static string $resource = VoiceoverTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
