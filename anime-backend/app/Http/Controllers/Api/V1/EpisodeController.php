<?php

namespace AnimeSite\Http\Controllers\Api\V1;

use AnimeSite\Actions\Episodes\CreateEpisode;
use AnimeSite\Actions\Episodes\GetEpisodes;
use AnimeSite\Actions\Episodes\UpdateEpisode;
use AnimeSite\DTOs\Episodes\EpisodeIndexDTO;
use AnimeSite\DTOs\Episodes\EpisodeStoreDTO;
use AnimeSite\DTOs\Episodes\EpisodeUpdateDTO;
use AnimeSite\Enums\UserListType;
use AnimeSite\Http\Requests\Episodes\EpisodeAiredAfterRequest;
use AnimeSite\Http\Requests\Episodes\EpisodeDeleteRequest;
use AnimeSite\Http\Requests\Episodes\EpisodeIndexRequest;
use AnimeSite\Http\Requests\Episodes\EpisodeStoreRequest;
use AnimeSite\Http\Requests\Episodes\EpisodeUpdateRequest;
use AnimeSite\Http\Resources\CommentResource;
use AnimeSite\Http\Resources\EpisodeDetailResource;
use AnimeSite\Http\Resources\EpisodeResource;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Episode;
use AnimeSite\Models\WatchHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class EpisodeController extends Controller
{
    /**
     * Get paginated list of episodes with filtering, sorting and pagination
     *
     * @param  EpisodeIndexRequest  $request
     * @param  GetEpisodes  $action
     * @return AnonymousResourceCollection
     */
    public function index(EpisodeIndexRequest $request, GetEpisodes $action): AnonymousResourceCollection
    {
        $dto = EpisodeIndexDTO::fromRequest($request);
        $episodes = $action->handle($dto);

        return EpisodeResource::collection($episodes);
    }

    /**
     * Get episodes aired after a specific date
     *
     * @param  EpisodeAiredAfterRequest  $request
     * @param  GetEpisodes  $action
     * @return AnonymousResourceCollection
     *
     * @urlParam date string required The date in YYYY-MM-DD format. Example: 2024-01-01
     */
    public function airedAfter(string $date, EpisodeAiredAfterRequest $request, GetEpisodes $action): AnonymousResourceCollection
    {
        // Merge aired_after date from route parameter
        $request->merge(['aired_after' => $date]);
        $dto = EpisodeIndexDTO::fromRequest($request);
        $episodes = $action->handle($dto);

        return EpisodeResource::collection($episodes);
    }

    /**
     * Get detailed information about a specific episode
     *
     * @param  Episode  $episode
     * @return EpisodeDetailResource
     */
    public function show(Episode $episode): EpisodeDetailResource
    {
        return new EpisodeDetailResource($episode->load(['anime']));
    }

    /**
     * Get episodes for a specific anime
     *
     * @param  Anime  $anime
     * @param  EpisodeIndexRequest  $request
     * @param  GetEpisodes  $action
     * @return AnonymousResourceCollection
     */
    public function forAnime(Anime $anime, EpisodeIndexRequest $request, GetEpisodes $action): AnonymousResourceCollection
    {
        // Merge anime_id into the request to use the fromRequest method
        $request->merge(['anime_id' => $anime->id]);
        $dto = EpisodeIndexDTO::fromRequest($request);
        $episodes = $action->handle($dto);

        return EpisodeResource::collection($episodes);
    }
    /**
     * Get comments for a specific anime
     *
     * @param  Episode  $episode
     * @return AnonymousResourceCollection
     */
    public function comments(Episode  $episode): AnonymousResourceCollection
    {
        $comments = $episode->comments()->paginate();

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created episode
     *
     * @param  EpisodeStoreRequest  $request
     * @param  CreateEpisode  $action
     * @return EpisodeDetailResource
     * @authenticated
     */
    public function store(EpisodeStoreRequest $request, CreateEpisode $action): EpisodeDetailResource
    {
        $dto = EpisodeStoreDTO::fromRequest($request);
        $episode = $action->handle($dto);

        return new EpisodeDetailResource($episode);
    }

    /**
     * Update the specified episode
     *
     * @param  EpisodeUpdateRequest  $request
     * @param  Episode  $episode
     * @param  UpdateEpisode  $action
     * @return EpisodeDetailResource
     * @authenticated
     */
    public function update(EpisodeUpdateRequest $request, Episode $episode, UpdateEpisode $action): EpisodeDetailResource
    {
        $dto = EpisodeUpdateDTO::fromRequest($request);
        $episode = $action->handle($episode, $dto);

        return new EpisodeDetailResource($episode);
    }

    /**
     * Remove the specified episode
     *
     * @param  EpisodeDeleteRequest  $request
     * @param  Episode  $episode
     * @return JsonResponse
     * @authenticated
     */
    public function destroy(EpisodeDeleteRequest $request, Episode $episode): JsonResponse
    {
        // Check if the episode has related content (e.g., comments)
        if ($episode->comments()->exists()) {
            return response()->json([
                'message' => 'Cannot delete episode with comments. Delete comments first.',
            ], 422);
        }

        $episode->delete();

        return response()->json([
            'message' => 'Episode deleted successfully',
        ]);
    }

    public function updateWatchProgress(Request $request, Episode $episode)
    {
        $user = $request->user();
        $progress = (int) $request->input('progress_time', 0); // у секундах

        $history = WatchHistory::updateOrCreate(
            ['user_id' => $user->id, 'episode_id' => $episode->id],
            ['progress_time' => $progress]
        );

        // Завантажити пов'язаний епізод і аніме
        $history->load('episode.anime');

        return response()->json([
            'message' => 'Progress saved',
            'data' => [
                'id' => $history->id,
                'progress_time' => $history->progress_time,
                'watched_at' => $history->updated_at,
                'episode' => [
                    'id' => $history->episode->id,
                    'name' => $history->episode->name,
                    'number' => $history->episode->number,
                    'air_date' => optional($history->episode->air_date)->format('Y-m-d'),
                    'anime' => [
                        'id' => $history->episode->anime->id,
                        'name' => $history->episode->anime->name,
                        'poster' => $history->episode->anime->poster,
                    ],
                ],
            ]
        ]);
    }
}
