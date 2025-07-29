<?php

namespace AnimeSite\Filament\Resources\VoiceoverTeamResource\Pages;

use AnimeSite\Filament\Resources\VoiceoverTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVoiceoverTeam extends EditRecord
{
    protected static string $resource = VoiceoverTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
