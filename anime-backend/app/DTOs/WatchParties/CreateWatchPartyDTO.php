<?php

namespace AnimeSite\DTOs\WatchParties;

class CreateWatchPartyDTO
{
    public function __construct(
        public string $name,
        public string $episodeId,
        public bool $isPrivate = false,
        public ?string $password = null,
        public int $maxViewers = 10,
    ) {}

    public static function fields(): array
    {
        return [
            'name' => 'name',
            'episode_id' => 'episodeId',
            'is_private' => 'isPrivate',
            'password' => 'password',
            'max_viewers' => 'maxViewers',
        ];
    }
    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return new self(
            name: $request->input('name'),
            episodeId: $request->input('episode_id'),
            isPrivate: $request->boolean('is_private', false),
            password: $request->input('password'),
            maxViewers: $request->input('max_viewers', 10),
        );
    }
}
