<?php

namespace AnimeSite\Actions\Studios;
use Illuminate\Support\Facades\Gate;
use AnimeSite\Models\Studio;

class ShowStudio
{
    public function __invoke(Studio $studio): Studio
    {
        // Gate::authorize('view', $studio); // Дозволяємо перегляд студій без авторизації
        return $studio->loadMissing(['animes']);
    }
}
