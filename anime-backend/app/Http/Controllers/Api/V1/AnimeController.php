<?php

namespace AnimeSite\Http\Controllers\Api\V1;

use AnimeSite\Actions\Animes\GetPopularAnimes;
use AnimeSite\DTOs\Animes\AnimeIndexDTO;
use AnimeSite\DTOs\Animes\AnimeStoreDTO;
use AnimeSite\DTOs\Animes\AnimeUpdateDTO;
use AnimeSite\DTOs\Animes\PopularsAnimesDTO;
use AnimeSite\Http\Requests\Animes\AnimeDeleteRequest;
use AnimeSite\Http\Requests\Animes\AnimeIndexRequest;
use AnimeSite\Http\Requests\Animes\AnimeStoreRequest;
use AnimeSite\Http\Requests\Animes\AnimeUpdateRequest;
use AnimeSite\Http\Resources\AnimeDetailResource;
use AnimeSite\Http\Resources\PersonResource;
use AnimeSite\Http\Resources\TagResource;
use AnimeSite\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use AnimeSite\Actions\Animes\CreateAnime;
use AnimeSite\Actions\Animes\GetAllAnimes;
use AnimeSite\Actions\Animes\UpdateAnime;
use AnimeSite\Http\Controllers\Controller;
use AnimeSite\Http\Resources\AnimeResource;
use AnimeSite\Http\Resources\CommentResource;
use AnimeSite\Http\Resources\EpisodeResource;
use AnimeSite\Http\Resources\RatingResource;
use AnimeSite\Models\Anime;

class AnimeController extends Controller
{
    /**
     * Get paginated list of anime with search, filtering, sorting and pagination
     *
     * @param  AnimeIndexRequest  $request
     * @param  GetAllAnimes  $action
     * @return AnonymousResourceCollection
     */
    public function index(AnimeIndexRequest $request, GetAllAnimes $action): AnonymousResourceCollection
    {
        $dto = AnimeIndexDTO::fromRequest($request);
        $animes = $action->handle($dto);

        return AnimeResource::collection($animes);
    }
    /**
     * Get paginated list of anime with search, filtering, sorting and pagination
     * @param  GetAllAnimes  $action
     * @param AnimeIndexRequest $request
     * @return AnonymousResourceCollection
     */
    public function top100( AnimeIndexRequest $request, GetAllAnimes $action): AnonymousResourceCollection
    {
        $dto = AnimeIndexDTO::fromRequest($request);

        $ranking = Anime::query()
            ->orderByDesc('imdb_score')
            ->pluck('id')
            ->values();

        $rankMap = $ranking->flip()->map(fn($i) => $i + 1);

        $animes = $action->handle($dto);

        $animes->setCollection(
            $animes->getCollection()->map(function ($anime) use ($rankMap) {
                $anime->rank = $rankMap[$anime->id] ?? null;
                return $anime;
            })
        );

        return AnimeResource::collection($animes);
    }

    /**
     * Get popular animes
     *
     * @param Request $request
     * @param GetPopularAnimes $action
     * @return AnonymousResourceCollection
     */
    public function popular(Request $request, GetPopularAnimes $action): AnonymousResourceCollection
    {
        $dto = PopularsAnimesDTO::fromRequest($request);
        $animes = $action->handle($dto);

        return AnimeResource::collection($animes);
    }

    /**
     * Get detailed information about a specific anime
     *
     * @param  Anime  $anime
     * @return AnimeDetailResource
     */
    public function show( Anime $anime): AnimeDetailResource
    {
        //$sortOrder = $request->query('episode_sort', 'desc');

        return new AnimeDetailResource($anime->load(['studio',
            'people',
            'tags',
            'comments.user',
            'comments.children',
            'comments.children.user',
            'comments.children.likes',
            'comments.children.children',
            'comments.children.children.user',
            'comments.children.children.likes',
            'comments.likes',
            'ratings.user',
            'episodes',
            ])
        );
    }

    /**
     * Get episodes for a specific anime
     *
     * @param  Anime  $anime
     * @return AnonymousResourceCollection
     */
    public function episodes(Anime $anime): AnonymousResourceCollection
    {
        $episodes = $anime->episodes()->paginate();

        return EpisodeResource::collection($episodes);
    }

    /**
     * Get persons associated with a specific anime
     *
     * @param  Anime  $anime
     * @return AnonymousResourceCollection
     */
    public function characters(Anime $anime): AnonymousResourceCollection
    {
        $persons = $anime->people()->where('type', 'character')->paginate();

        return PersonResource::collection($persons);
    }

    /**
     * Get tags associated with a specific anime
     *
     * @param  Anime  $anime
     * @return AnonymousResourceCollection
     */
    public function tags(Anime $anime): JsonResponse
    {
        // Отримуємо всі теги без пагінації
        $tags = $anime->tags()->get();

        // Повертаємо тільки назви тегів як простий масив
        $tagNames = $tags->pluck('name')->toArray();

        return response()->json([
            'data' => $tagNames
        ]);
    }

    /**
     * Get ratings for a specific anime
     *
     * @param  Anime  $anime
     * @return AnonymousResourceCollection
     */
    public function ratings(Anime $anime): AnonymousResourceCollection
    {
        $ratings = $anime->ratings()->paginate();

        return RatingResource::collection($ratings);
    }

    /**
     * Get comments for a specific anime
     *
     * @param  Anime  $anime
     * @return AnonymousResourceCollection
     */
    public function comments(Anime $anime): AnonymousResourceCollection
    {
        $comments = $anime->comments()->paginate();

        return CommentResource::collection($comments);
    }

    /**
     * Get similars for a specific anime
     *
     * @param  Anime  $anime
     * @return AnonymousResourceCollection
     */
    public function similars(Anime $anime): AnonymousResourceCollection
    {
        $similars = $anime->getSimilarAnime()->paginate();

        return AnimeResource::collection($similars);
    }

    /**
     * Get releted for a specific anime
     *
     * @param  Anime  $anime
     * @return AnonymousResourceCollection
     */
    public function related(Anime $anime): AnonymousResourceCollection
    {
        $related = $anime->getRelatedAnimeWithType();

        return AnimeResource::collection($related);
    }


    public function media(Anime $anime): JsonResponse
    {
        $attachments = collect($anime->attachments ?? []);

        // Group by type and sort URLs within each group
        $groupedMedia = $attachments
            ->groupBy('type')
            ->map(function ($items) {
                return $items->sortBy('url')->values();
            })
            ->sortKeys(); // Sort the groups by type name

        return response()->json([
            'data' => $groupedMedia
        ]);
    }


    /**
     * Store a newly created anime
     *
     * @param  AnimeStoreRequest  $request
     * @param  CreateAnime  $action
     * @return AnimeDetailResource
     * @authenticated
     */
    public function store(AnimeStoreRequest $request, CreateAnime $action): AnimeDetailResource
    {
        $dto = AnimeStoreDTO::fromRequest($request);
        $anime = $action->handle($dto);

        return new AnimeDetailResource($anime);
    }

    /**
     * Update the specified anime
     *
     * @param  AnimeUpdateRequest  $request
     * @param  Anime  $anime
     * @param  UpdateAnime  $action
     * @return AnimeDetailResource
     * @authenticated
     */
    public function update(AnimeUpdateRequest $request, Anime $anime, UpdateAnime $action): AnimeDetailResource
    {
        $dto = AnimeUpdateDTO::fromRequest($request);
        $anime = $action->handle($anime, $dto);

        return new AnimeDetailResource($anime);
    }

    /**
     * Update specific fields of the anime
     *
     * @param  AnimeUpdateRequest  $request
     * @param  Anime  $anime
     * @param  UpdateAnime  $action
     * @return AnimeDetailResource
     * @authenticated
     */
    public function updatePartial(AnimeUpdateRequest $request, Anime $anime, UpdateAnime $action): AnimeDetailResource
    {
        $dto = AnimeUpdateDTO::fromRequest($request);
        $anime = $action->handle($anime, $dto);

        return new AnimeDetailResource($anime);
    }

    /**
     * Remove the specified anime
     *
     * @param  AnimeDeleteRequest  $request
     * @param  Anime  $anime
     * @return JsonResponse
     * @authenticated
     */
    public function destroy(AnimeDeleteRequest $request, Anime $anime): JsonResponse
    {
        if ($anime->episodes()->exists()) {
            return response()->json([
                'message' => 'Cannot delete anime with episodes. Delete episodes first.',
            ], 422);
        }

        $anime->delete();

        return response()->json([
            'message' => 'Anime deleted successfully',
        ]);
    }

}
