<?php

namespace AnimeSite\Http\Controllers\Api\V1;

use AnimeSite\DTOs\Ratings\RatingIndexDTO;
use AnimeSite\DTOs\Ratings\RatingStoreDTO;
use AnimeSite\DTOs\Ratings\RatingUpdateDTO;
use AnimeSite\Http\Requests\Ratings\RatingDeleteRequest;
use AnimeSite\Http\Requests\Ratings\RatingIndexRequest;
use AnimeSite\Http\Requests\Ratings\RatingStoreRequest;
use AnimeSite\Http\Requests\Ratings\RatingUpdateRequest;
use AnimeSite\Http\Resources\UserRatingResource;
use AnimeSite\Models\Anime;
use AnimeSite\Models\User;
use Illuminate\Http\JsonResponse;
use AnimeSite\Actions\Ratings\CreateRating;
use AnimeSite\Actions\Ratings\GetRatings;
use AnimeSite\Actions\Ratings\UpdateRating;
use AnimeSite\Http\Controllers\Controller;
use AnimeSite\Http\Resources\RatingResource;
use AnimeSite\Models\Rating;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RatingController extends Controller
{
    /**
     * Get paginated list of ratings with filtering, sorting and pagination
     *
     * @param  RatingIndexRequest  $request
     * @param  GetRatings  $action
     * @return AnonymousResourceCollection
     */
    public function index(RatingIndexRequest $request, GetRatings $action): AnonymousResourceCollection
    {
        $dto = RatingIndexDTO::fromRequest($request);
        $ratings = $action->handle($dto);

        return RatingResource::collection($ratings);
    }

    /**
     * Store a newly created rating
     *
     * @param  RatingStoreRequest  $request
     * @param  CreateRating  $action
     * @return JsonResponse
     * @authenticated
     */
    public function store(RatingStoreRequest $request, CreateRating $action): JsonResponse
    {
        $dto = RatingStoreDTO::fromRequest($request);

        // Check if rating already exists before creating
        $existingRating = Rating::where('user_id', $dto->userId)
            ->where('anime_id', $dto->animeId)
            ->first();

        $rating = $action->handle($dto);
        $statusCode = $existingRating ? 200 : 201; // 200 for update, 201 for create

        return (new RatingResource($rating->load(['user', 'anime'])))
            ->response()
            ->setStatusCode($statusCode);
    }

    /**
     * Get detailed information about a specific rating
     *
     * @param  Rating  $rating
     * @return RatingResource
     */
    public function show(Rating $rating): RatingResource
    {
        return new RatingResource($rating->load(['user', 'anime']));
    }

    /**
     * Update the specified rating
     *
     * @param  RatingUpdateRequest  $request
     * @param  Rating  $rating
     * @param  UpdateRating  $action
     * @return RatingResource
     * @authenticated
     */
    public function update(RatingUpdateRequest $request, Rating $rating, UpdateRating $action): RatingResource
    {
        $dto = RatingUpdateDTO::fromRequest($request);
        $rating = $action->handle($rating, $dto);

        return new RatingResource($rating->load(['user', 'anime']));
    }

    /**
     * Remove the specified rating
     *
     * @param  RatingDeleteRequest  $request
     * @param  Rating  $rating
     * @return JsonResponse
     * @authenticated
     */
    public function destroy(RatingDeleteRequest $request, Rating $rating): JsonResponse
    {
        $rating->delete();

        return response()->json(['message' => 'Rating deleted successfully']);
    }

    /**
     * Get ratings for a specific user
     *
     * @param  User  $user
     * @param  RatingIndexRequest  $request
     * @param  GetRatings  $action
     * @return AnonymousResourceCollection
     */
    public function forUser(User $user, RatingIndexRequest $request, GetRatings $action): AnonymousResourceCollection
    {
        $request->merge(['user_id' => $user->id]);
        $dto = RatingIndexDTO::fromRequest($request);
        $ratings = $action->handle($dto);

        return UserRatingResource::collection($ratings);
    }

    /**
     * Get ratings for a specific anime
     *
     * @param  Anime  $anime
     * @param  RatingIndexRequest  $request
     * @param  GetRatings  $action
     * @return AnonymousResourceCollection
     */
    public function forAnime(Anime  $anime, RatingIndexRequest $request, GetRatings $action): AnonymousResourceCollection
    {
        $request->merge(['anime_id' => $anime->id]);
        $dto = RatingIndexDTO::fromRequest($request);
        $ratings = $action->handle($dto);

        return RatingResource::collection($ratings);
    }
}
