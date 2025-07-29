<?php

namespace AnimeSite\Http\Controllers\Api\V1;


use AnimeSite\Actions\Animes\GetAllAnimes;
use AnimeSite\Actions\Studios\CreateStudio;
use AnimeSite\Actions\Studios\GetStudios;
use AnimeSite\Actions\Studios\UpdateStudio;
use AnimeSite\DTOs\Animes\AnimeIndexDTO;
use AnimeSite\DTOs\Studios\StudioIndexDTO;
use AnimeSite\DTOs\Studios\StudioStoreDTO;
use AnimeSite\DTOs\Studios\StudioUpdateDTO;
use AnimeSite\Http\Requests\Animes\AnimeIndexRequest;
use AnimeSite\Http\Requests\Animes\AnimeSearchRequest;
use AnimeSite\Http\Requests\Studios\StudioDeleteRequest;
use AnimeSite\Http\Requests\Studios\StudioIndexRequest;
use AnimeSite\Http\Requests\Studios\StudioSearchRequest;
use AnimeSite\Http\Requests\Studios\StudioStoreRequest;
use AnimeSite\Http\Requests\Studios\StudioUpdateRequest;
use AnimeSite\Http\Resources\AnimeResource;
use AnimeSite\Http\Resources\StudioResource;
use AnimeSite\Models\Studio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class StudioController extends Controller
{
    /**
     * Get paginated list of studios with filtering, sorting and pagination
     *
     * @param  StudioIndexRequest  $request
     * @param  GetStudios  $action
     * @return AnonymousResourceCollection
     */
    public function index(StudioIndexRequest $request, GetStudios $action): AnonymousResourceCollection
    {
        $dto = StudioIndexDTO::fromRequest($request);
        $studios = $action->handle($dto);

        return StudioResource::collection($studios);
    }

    /**
     * Search for studios by name with filtering, sorting and pagination
     *
     * @param  string  $query
     * @param  StudioSearchRequest  $request
     * @param  GetStudios  $action
     * @return AnonymousResourceCollection
     */
    public function search(string $query, StudioSearchRequest $request, GetStudios $action): AnonymousResourceCollection
    {
        $request->merge(['q' => $query]);
        $dto = StudioIndexDTO::fromRequest($request);
        $studios = $action->handle($dto);

        return StudioResource::collection($studios);
    }

    /**
     * Get detailed information about a specific studio
     *
     * @param  Studio  $studio
     * @return StudioResource
     */
    public function show(Studio $studio): StudioResource
    {
        $studio->loadCount('animes');
        $studio->load(['animes' => function($query) {
            $query->with(['studio', 'tags', 'people', 'episodes']); // завантажуємо потрібні зв’язки аніме
        }]);

        return new StudioResource($studio);
    }

    /**
     * Get animes associated with a specific studio
     *
     * @param  Studio  $studio
     * @param AnimeIndexRequest $request
     * @param GetAllAnimes $action
     * @return AnonymousResourceCollection
     */
    public function animes(AnimeIndexRequest $request,Studio $studio,GetAllAnimes $action): AnonymousResourceCollection
    {
        $dto = AnimeIndexDTO::fromRequest($request);

        $dto = new AnimeIndexDTO(
            query: $dto->query,
            page: $dto->page,
            perPage: $dto->perPage,
            sort: $dto->sort,
            direction: $dto->direction,
            kinds: $dto->kinds,
            statuses: $dto->statuses,
            minScore: $dto->minScore,
            maxScore: $dto->maxScore,
            studioIds: [$studio->id],
            tagIds: $dto->tagIds,
            personIds: $dto->personIds,
            minYear: $dto->minYear,
            maxYear: $dto->maxYear,
            countries: $dto->countries,
            minDuration: $dto->minDuration,
            maxDuration: $dto->maxDuration,
            minEpisodesCount: $dto->minEpisodesCount,
            maxEpisodesCount: $dto->maxEpisodesCount,
        );

        $animes = $action->handle($dto);

        return AnimeResource::collection($animes);
    }

    /**
     * Store a newly created studio
     *
     * @param  StudioStoreRequest  $request
     * @param  CreateStudio  $action
     * @return StudioResource
     * @authenticated
     */
    public function store(StudioStoreRequest $request, CreateStudio $action): StudioResource
    {
        $dto = StudioStoreDTO::fromRequest($request);
        $studio = $action->handle($dto);

        return new StudioResource($studio);
    }

    /**
     * Update the specified studio
     *
     * @param  StudioUpdateRequest  $request
     * @param  Studio  $studio
     * @param  UpdateStudio  $action
     * @return StudioResource
     * @authenticated
     */
    public function update(StudioUpdateRequest $request, Studio $studio, UpdateStudio $action): StudioResource
    {
        $dto = StudioUpdateDTO::fromRequest($request);
        $studio = $action->handle($studio, $dto);

        return new StudioResource($studio);
    }

    /**
     * Remove the specified studio
     *
     * @param  StudioDeleteRequest  $request
     * @param  Studio  $studio
     * @return JsonResponse
     * @authenticated
     */
    public function destroy(StudioDeleteRequest $request, Studio $studio): JsonResponse
    {
        // Check if the studio has related animes
        if ($studio->animes()->exists()) {
            return response()->json([
                'message' => 'Cannot delete studio with associated animes. Remove associations first.',
            ], 422);
        }

        $studio->delete();

        return response()->json([
            'message' => 'Studio deleted successfully',
        ]);
    }
}
