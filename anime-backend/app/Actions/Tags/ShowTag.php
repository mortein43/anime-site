<?php

namespace AnimeSite\Actions\Tags;

use Illuminate\Support\Facades\Gate;
use AnimeSite\Models\Tag;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowTag
{
    public function __invoke(Tag $tag): Tag
    {
        // Gate::authorize('view', $tag); // Дозволяємо перегляд тегів без авторизації
        return $tag->loadMissing(['parent', 'children', 'animes']);
    }
}
