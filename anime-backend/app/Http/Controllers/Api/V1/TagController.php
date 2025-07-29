<?php

namespace AnimeSite\Http\Controllers\Api\V1;

use AnimeSite\Actions\Tags\CreateTag;
use AnimeSite\Actions\Tags\GetGenres;
use AnimeSite\Actions\Tags\GetTags;
use AnimeSite\Actions\Tags\UpdateTag;
use AnimeSite\DTOs\Tags\TagIndexDTO;
use AnimeSite\DTOs\Tags\TagStoreDTO;
use AnimeSite\DTOs\Tags\TagUpdateDTO;
use AnimeSite\Http\Requests\Tags\TagDeleteRequest;
use AnimeSite\Http\Requests\Tags\TagIndexRequest;
use AnimeSite\Http\Requests\Tags\TagStoreRequest;
use AnimeSite\Http\Requests\Tags\TagUpdateRequest;
use AnimeSite\Http\Resources\AnimeResource;
use AnimeSite\Http\Resources\PersonResource;
use AnimeSite\Http\Resources\SelectionResource;
use AnimeSite\Http\Resources\TagResource;
use AnimeSite\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Get paginated list of tags with filtering, sorting and pagination
     *
     * @param  TagIndexRequest  $request
     * @param  GetTags  $action
     * @return AnonymousResourceCollection
     */
    public function index(TagIndexRequest $request, GetTags $action): AnonymousResourceCollection
    {
        $dto = TagIndexDTO::fromRequest($request);
        $tags = $action->handle($dto);

        return TagResource::collection($tags);
    }

    /**
     * Get paginated list of tags with filtering, sorting and pagination
     *
     * @param  TagIndexRequest  $request
     * @param  GetGenres  $action
     * @return AnonymousResourceCollection
     */
    public function genres(TagIndexRequest $request, GetGenres $action): AnonymousResourceCollection
    {
        $dto = TagIndexDTO::fromRequest($request);

        $tags = $action->handle($dto);

        return TagResource::collection($tags);
    }
    /**
     * Get detailed information about a specific tag
     *
     * @param  Tag  $tag
     * @return TagResource
     */
    public function show(Request $request, Tag $tag): TagResource
    {
        $sortAnime = $request->get('sort_anime', 'name');   // сортування аніме
        $sortPeople = $request->get('sort_people', 'name'); // сортування людей

        $tag->loadCount('animes', 'people');

        $tag->load([
            'animes' => function ($query) use ($sortAnime) {
                if ($sortAnime === 'rating') {
                    $query->orderByDesc('imdb_score');
                } elseif ($sortAnime === 'year') {
                    $query->orderByDesc('first_air_date');
                } else {
                    $query->orderBy('name');
                }
            },
            'people' => function ($query) use ($sortPeople) {
                if ($sortPeople === 'age') {
                    // Для сортування за віком — сортуємо за датою народження (birthday)
                    // Молодші мають більшу дату народження, тому сортуємо по зростанню дати
                    $query->orderBy('birthday');
                } else {
                    $query->orderBy('name');
                }
            },
        ]);

        return new TagResource($tag);
    }
    /**
     * Get detailed information about a specific tag
     *
     * @param  Tag  $tag
     * @return TagResource
     */
    public function showGenre(Request $request, Tag $genre): TagResource
    {
        $sortAnime = $request->get('sort_anime', 'name');   // сортування аніме

        $genre->loadCount('animes', 'people');

        $genre->load([
            'animes' => function ($query) use ($sortAnime) {
                if ($sortAnime === 'rating') {
                    $query->orderByDesc('imdb_score');
                } elseif ($sortAnime === 'year') {
                    $query->orderByDesc('first_air_date');
                } else {
                    $query->orderBy('name');
                }
            },
        ]);

        return new TagResource($genre);
    }
    /**
     * Get animes associated with a specific tag
     *
     * @param  Tag  $tag
     * @return AnonymousResourceCollection
     */
    public function animes(Tag $tag): AnonymousResourceCollection
    {
        $animes = $tag->animes()->paginate();

        return AnimeResource::collection($animes);
    }
    /**
     * Get animes associated with a specific tag
     *
     * @param  Tag  $tag
     * @return AnonymousResourceCollection
     */
    public function genreAnimes(Tag $genre): AnonymousResourceCollection
    {
        $animes = $genre->animes()->paginate();

        return AnimeResource::collection($animes);
    }
    /**
     * Get people associated with a specific tag
     *
     * @param  Tag  $tag
     * @return AnonymousResourceCollection
     */
    public function people(Tag $tag): AnonymousResourceCollection
    {
        $people = $tag->people()->paginate();
        return PersonResource::collection($people);
    }
    /**
     * Get selections associated with a specific tag
     *
     * @param  Tag  $tag
     * @return AnonymousResourceCollection
     */

    public function selections(Tag $tag): AnonymousResourceCollection
    {
        $selections = $tag->selections()->paginate();
        return SelectionResource::collection($selections);
    }

    /**
     * Store a newly created tag
     *
     * @param  TagStoreRequest  $request
     * @param  CreateTag  $action
     * @return TagResource
     * @authenticated
     */
    public function store(TagStoreRequest $request, CreateTag $action): TagResource
    {
        $dto = TagStoreDTO::fromRequest($request);
        $tag = $action->handle($dto);

        return new TagResource($tag);
    }

    /**
     * Update the specified tag
     *
     * @param  TagUpdateRequest  $request
     * @param  Tag  $tag
     * @param  UpdateTag  $action
     * @return TagResource
     * @authenticated
     */
    public function update(TagUpdateRequest $request, Tag $tag, UpdateTag $action): TagResource
    {
        $dto = TagUpdateDTO::fromRequest($request);
        $tag = $action->handle($tag, $dto);

        return new TagResource($tag);
    }

    /**
     * Remove the specified tag
     *
     * @param  TagDeleteRequest  $request
     * @param  Tag  $tag
     * @return JsonResponse
     * @authenticated
     */
    public function destroy(TagDeleteRequest $request, Tag $tag): JsonResponse
    {
        // Check if the tag has related movies
        if ($tag->animes()->exists()) {
            return response()->json([
                'message' => 'Cannot delete tag with associated animes. Remove associations first.',
            ], 422);
        }

        $tag->delete();

        return response()->json([
            'message' => 'Tag deleted successfully',
        ]);
    }
}
