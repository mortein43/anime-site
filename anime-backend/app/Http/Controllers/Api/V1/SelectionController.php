<?php

namespace AnimeSite\Http\Controllers\Api\V1;

use AnimeSite\Actions\Selections\CreateSelection;
use AnimeSite\Actions\Selections\GetSelections;
use AnimeSite\Actions\Selections\UpdateSelection;
use AnimeSite\DTOs\Selections\SelectionIndexDTO;
use AnimeSite\DTOs\Selections\SelectionStoreDTO;
use AnimeSite\DTOs\Selections\SelectionUpdateDTO;
use AnimeSite\Http\Requests\Selections\SelectionDeleteRequest;
use AnimeSite\Http\Requests\Selections\SelectionIndexRequest;
use AnimeSite\Http\Requests\Selections\SelectionStoreRequest;
use AnimeSite\Http\Requests\Selections\SelectionUpdateRequest;
use AnimeSite\Http\Resources\AnimeResource;
use AnimeSite\Http\Resources\EpisodeResource;
use AnimeSite\Http\Resources\PersonResource;
use AnimeSite\Http\Resources\SelectionResource;
use AnimeSite\Models\Selection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class SelectionController extends Controller
{
    /**
     * Get paginated list of selections with filtering, sorting and pagination
     *
     * @param  SelectionIndexRequest  $request
     * @param  GetSelections  $action
     * @return AnonymousResourceCollection
     */
    public function index(SelectionIndexRequest $request, GetSelections $action): AnonymousResourceCollection
    {
        $dto = SelectionIndexDTO::fromRequest($request);
        $selections = $action->handle($dto);

        return SelectionResource::collection($selections);
    }

    /**
     * Get detailed information about a specific selection
     *
     * @param  Selection  $selection
     * @return SelectionResource
     */
    public function show(Selection $selection): SelectionResource
    {
        return new SelectionResource($selection->load(['user', 'animes', 'persons','episodes'])->loadCount(['animes', 'userLists']));
    }

    /**
     * Get animes associated with a specific selection
     *
     * @param  Selection  $selection
     * @return AnonymousResourceCollection
     */
    public function animes(Selection $selection): AnonymousResourceCollection
    {
        $animes = $selection->animes()->paginate();

        return AnimeResource::collection($animes);
    }

    /**
     * Get persons associated with a specific selection
     *
     * @param  Selection  $selection
     * @return AnonymousResourceCollection
     */
    public function persons(Selection $selection): AnonymousResourceCollection
    {
        $persons = $selection->persons()->paginate();

        return PersonResource::collection($persons);
    }
    /**
     * Get episodes associated with a specific selection
     *
     * @param  Selection  $selection
     * @return AnonymousResourceCollection
     */
    public function episodes(Selection $selection): AnonymousResourceCollection
    {
        $episodes = $selection->episodes()->paginate();

        return EpisodeResource::collection($episodes);
    }

    /**
     * Store a newly created selection
     *
     * @param  SelectionStoreRequest  $request
     * @param  CreateSelection  $action
     * @return SelectionResource
     * @authenticated
     */
    public function store(SelectionStoreRequest $request, CreateSelection $action): SelectionResource
    {
        $dto = SelectionStoreDTO::fromRequest($request);
        $selection = $action->handle($dto);

        return new SelectionResource($selection);
    }

    /**
     * Update the specified selection
     *
     * @param  SelectionUpdateRequest  $request
     * @param  Selection  $selection
     * @param  UpdateSelection  $action
     * @return SelectionResource
     * @authenticated
     */
    public function update(SelectionUpdateRequest $request, Selection $selection, UpdateSelection $action): SelectionResource
    {
        $dto = SelectionUpdateDTO::fromRequest($request);
        $selection = $action->handle($selection, $dto);

        return new SelectionResource($selection);
    }

    /**
     * Remove the specified selection
     *
     * @param  SelectionDeleteRequest  $request
     * @param  Selection  $selection
     * @return JsonResponse
     * @authenticated
     */
    public function destroy(SelectionDeleteRequest $request, Selection $selection): JsonResponse
    {
        $selection->animes()->detach();
        $selection->persons()->detach();
        $selection->episodes()->detach();
        $selection->delete();

        return response()->json([
            'message' => 'Selection deleted successfully',
        ]);
    }
}
