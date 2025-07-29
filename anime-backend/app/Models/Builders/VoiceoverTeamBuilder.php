<?php

namespace AnimeSite\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class VoiceoverTeamBuilder  extends Builder
{
    public function byName(string $name): self
    {
        return $this->where('name', 'like', '%'.$name.'%');
    }
}
