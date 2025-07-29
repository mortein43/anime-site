<?php

namespace AnimeSite\Actions\People;

use Illuminate\Support\Facades\Gate;
use AnimeSite\Models\Person;

class ShowPerson
{
    public function __invoke(Person $person): Person
    {
        // Gate::authorize('view', $person); // Дозволяємо перегляд персон без авторизації
        return $person->loadMissing(['animes']);
    }
}
