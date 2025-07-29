<?php

namespace AnimeSite\Models\Builders;

use AnimeSite\Enums\Gender;
use AnimeSite\Enums\PersonType;
use Illuminate\Database\Eloquent\Builder;

class PersonQueryBuilder extends Builder
{
    /**
     * Filter by person type.
     *
     * @param PersonType $type
     * @return self
     */
    public function byType(PersonType $type): self
    {
        return $this->where('type', $type->value);
    }

    /**
     * Filter by name.
     *
     * @param string $name
     * @return self
     */
    public function byName(string $name): self
    {
        return $this->where('name', 'like', '%'.$name.'%');
    }

    /**
     * Filter by gender.
     *
     * @param Gender|string $gender
     * @return self
     */
    public function byGender(Gender|string $gender): self
    {
        if ($gender instanceof Gender) {
            return $this->where('gender', $gender->value);
        }

        return $this->where('gender', $gender);
    }
    /**
     * Get characters.
     *
     * @return self
     */
    public function characters(): self
    {
        return $this->byType(PersonType::CHARACTER);
    }

    /**
     * Get actors.
     *
     * @return self
     */
    public function voiceActors(): self
    {
        return $this->byType(PersonType::VOICE_ACTOR);
    }

    /**
     * Get directors.
     *
     * @return self
     */
    public function directors(): self
    {
        return $this->byType(PersonType::DIRECTOR);
    }

    /**
     * Get writers.
     *
     * @return self
     */
    public function scriptwriters(): self
    {
        return $this->byType(PersonType::SCRIPTWRITER);
    }

    /**
     * Get persons with animes.
     *
     * @return self
     */
    public function withAnimes(): self
    {
        return $this->whereHas('animes');
    }

    /**
     * Get persons with anime count.
     *
     * @return self
     */
    public function withAnimeCount(): self
    {
        return $this->withCount('animes');
    }

    /**
     * Order by anime count.
     *
     * @param string $direction
     * @return self
     */
    public function orderByAnimeCount(string $direction = 'desc'): self
    {
        return $this->withAnimeCount()->orderBy('animes_count', $direction);
    }
}
