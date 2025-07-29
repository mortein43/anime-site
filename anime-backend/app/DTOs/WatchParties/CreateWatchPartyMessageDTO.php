<?php

namespace AnimeSite\DTOs\WatchParties;

class CreateWatchPartyMessageDTO
{
    public function __construct(
        public string $message
    ) {}

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return new self(
            message: $request->input('message')
        );
    }
}
