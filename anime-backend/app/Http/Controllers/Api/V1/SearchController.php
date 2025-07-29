<?php

namespace AnimeSite\Http\Controllers\Api\V1;

use AnimeSite\Actions\Search\PerformAutocomplete;
use AnimeSite\Actions\Search\PerformSearch;
use AnimeSite\DTOs\Search\AutocompleteDTO;
use AnimeSite\DTOs\Search\SearchDTO;
use AnimeSite\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use AnimeSite\Http\Requests\Search\SearchRequest;
use AnimeSite\Http\Requests\Search\AutocompleteRequest;

class SearchController extends Controller
{
    /**
     * Search across all content types
     *
     * @param SearchRequest $request
     * @param PerformSearch $action
     * @return JsonResponse
     */
    public function search(SearchRequest $request, PerformSearch $action): JsonResponse
    {
        $dto = SearchDTO::fromRequest($request);
        $results = $action->handle($dto);

        return response()->json($results);
    }

    /**
     * Autocomplete search for quick suggestions
     *
     * @param AutocompleteRequest $request
     * @param PerformAutocomplete $action
     * @return JsonResponse
     */
    public function autocomplete(AutocompleteRequest $request, PerformAutocomplete $action): JsonResponse
    {
        $dto = AutocompleteDTO::fromRequest($request);
        $results = $action->handle($dto);

        return response()->json($results);
    }
}
