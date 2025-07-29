<?php

namespace AnimeSite\DTOs\Selections;

use AnimeSite\DTOs\BaseDTO;
use Illuminate\Http\Request;

class SelectionIndexDTO extends BaseDTO
{
    /**
     * Create a new SelectionIndexDTO instance.
     *
     * @param string|null $query Search query
     * @param int $page Current page number
     * @param int $perPage Number of items per page
     * @param string|null $sort Field to sort by
     * @param string $direction Sort direction (asc or desc)
     * @param bool|null $isPublished Filter by published status
     * @param string|null $userId Filter by user ID
     * @param bool|null $hasAnimes Filter selections that have animes
     * @param bool|null $hasPersons Filter selections that have persons
     * @param bool|null $hasEpisodes Filter selections that have episodes
     * @param array|null $animeIds Filter by anime IDs
     * @param array|null $personIds Filter by person IDs
     * @param array|null $episodeIds Filter by person IDs
     */
    public function __construct(
        public readonly ?string $query = null,
        public readonly int $page = 1,
        public readonly int $perPage = 15,
        public readonly ?string $sort = 'created_at',
        public readonly string $direction = 'desc',
        public readonly ?bool $isPublished = null,
        public readonly ?string $userId = null,
        public readonly ?bool $hasAnimes = null,
        public readonly ?bool $hasPersons = null,
        public readonly ?bool $hasEpisodes = null,
        public readonly ?array $animeIds = null,
        public readonly ?array $personIds = null,
        public readonly ?array $episodeIds = null,
    ) {
    }

    /**
     * Get the fields that should be used for the DTO.
     *
     * @return array
     */
    public static function fields(): array
    {
        return [
            'q' => 'query',
            'page',
            'per_page' => 'perPage',
            'sort',
            'direction',
            'is_published' => 'isPublished',
            'user_id' => 'userId',
            'has_animes' => 'hasAnimes',
            'has_persons' => 'hasPersons',
            'has_episodes' => 'hasEpisodes',
            'anime_ids' => 'animeIds',
            'person_ids' => 'personIds',
            'episode_ids' => 'episodeIds',
        ];
    }

    /**
     * Create a new DTO instance from request.
     *
     * @param Request $request
     * @return static
     */
    public static function fromRequest(Request $request): static
    {
        // Process anime IDs array
        $animeIds = self::processArrayInput($request, 'anime_ids');

        // Process person IDs array
        $personIds = self::processArrayInput($request, 'person_ids');

        // Process episode IDs array
        $episodeIds = self::processArrayInput($request, 'episode_ids');

        return new static(
            query: $request->input('q'),
            page: (int) $request->input('page', 1),
            perPage: (int) $request->input('per_page', 15),
            sort: $request->input('sort', 'created_at'),
            direction: $request->input('direction', 'desc'),
            isPublished: $request->has('is_published') ? (bool) $request->input('is_published') : null,
            userId: $request->input('user_id'),
            hasAnimes: $request->has('has_animes') ? (bool) $request->input('has_animes') : null,
            hasPersons: $request->has('has_persons') ? (bool) $request->input('has_persons') : null,
            hasEpisodes: $request->has('has_episodes') ? (bool) $request->input('has_episodes') : null,
            animeIds: $animeIds,
            personIds: $personIds,
            episodeIds: $episodeIds,
        );
    }

    /**
     * Process array input from request
     *
     * @param Request $request
     * @param string $key
     * @return array|null
     */
    private static function processArrayInput(Request $request, string $key): ?array
    {
        if (!$request->has($key)) {
            return null;
        }

        $input = $request->input($key);
        if (is_string($input)) {
            return explode(',', $input);
        }

        return $input;
    }
}
