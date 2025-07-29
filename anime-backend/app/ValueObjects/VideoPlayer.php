<?php

namespace AnimeSite\ValueObjects;


use AnimeSite\Enums\VideoPlayerName;
use AnimeSite\Enums\VideoQuality;


class VideoPlayer
{
    public function __construct(
        public string $file_url,
        public string $voiceover_team_id,
    ) {}

}
