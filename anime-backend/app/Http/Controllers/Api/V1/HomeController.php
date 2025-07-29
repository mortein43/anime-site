<?php

namespace AnimeSite\Http\Controllers\Api\V1;

use AnimeSite\Enums\UserListType;
use AnimeSite\Http\Controllers\Controller;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Comment;
use AnimeSite\Models\Episode;
use AnimeSite\Models\Rating;
use AnimeSite\Models\Tag;
use AnimeSite\Models\User;
use AnimeSite\Models\WatchHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Форматує аніме у потрібний масив із полями.
     * Якщо переданий $rank — додає його.
     */
    private function formatAnime(Anime $anime, ?int $rank = null): array
    {
        $count = $anime->seasonsCount();

        $result = [
            'id' => $anime->id,
            'name' => $anime->name,
            'poster' => $anime->poster,
            'imdb_score' => $anime->imdb_score,
            'duration' => $anime->duration,
            'related_seasons_count' => $count + 1,
            'year' => $anime->first_air_date ? $anime->first_air_date->year : null,
            'kind'=> $anime->kind->name(),
            'slug'=>$anime->slug,
        ];

        if ($rank !== null) {
            $result['rank'] = $rank;
        }

        return $result;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. 5 аніме: постер, назва, опис (форматування трохи інше — опис є)
        $fiveAnime = Anime::select('id', 'name', 'description', 'poster', 'slug')
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(function ($anime) {
                // Додаємо related_seasons_count
                $count = $anime->seasonsCount();

                // Забезпечуємо правильний URL постеру
                $posterUrl = $anime->poster;
                if (!str_starts_with($posterUrl, 'https://storageanimesite.blob.core.windows.net/images')) {
                    // Якщо постер не починається з повного URL, додаємо базовий URL
                    $posterUrl = 'https://storageanimesite.blob.core.windows.net/images/' . ltrim($posterUrl, '/');
                }

                return [
                    'id' => $anime->id,
                    'name' => $anime->name,
                    'description' => strip_tags($anime->description),
                    'poster' => $posterUrl,
                    'slug' => $anime->slug, // Додаємо slug для навігації
                    'related_seasons_count' => $count + 1,
                ];
            });

        // 2. Продовжуйте дивитися (для залогінених)
        $continueWatching = collect();
        if ($user) {
            $continueWatching = WatchHistory::with('episode.anime')
                ->where('user_id', $user->id)
                ->where('progress_time', '>', 0)
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get()
                ->map(function ($wh) {
                    return [
                        'episode_id' => $wh->episode->id,
                        'episode_number' => $wh->episode->number,
                        'anime_id' => $wh->episode->anime->id,
                        'anime_name' => $wh->episode->anime->name,
                        'poster' => $wh->episode->anime->poster,
                        'progress_time' => $wh->progress_time,
                    ];
                });
        }

        // 3. Популярне зараз — 4 аніме за кількістю WatchHistory
        $popularNow = Anime::withCount('watchHistories')
            ->orderByDesc('watch_histories_count')
            ->limit(4)
            ->get()
            ->map(fn($anime) => $this->formatAnime($anime));

        // 4. Топ 10 за imdb_score з rank (якщо rank є)
        $top10 = Anime::orderByDesc('imdb_score')
            ->limit(10)
            ->get()
            ->values()
            ->map(fn($anime, $index) => $this->formatAnime($anime, $index + 1));

        // 5. Останні 5 коментарів
        $latestComments = Comment::with('user')
            ->whereIn('commentable_type', [Anime::class, Episode::class])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($comment) {
                $title = null;
                $typeLabel = null;

                if ($comment->commentable_type === Anime::class) {
                    $anime = Anime::find($comment->commentable_id);
                    $title = $anime?->name;
                    $typeLabel = 'Аніме';
                    $url = $anime ? 'http://localhost:3000/anime/' . $anime->slug : null;
                } elseif ($comment->commentable_type === Episode::class) {
                    $episode = Episode::find($comment->commentable_id);
                    $title = $episode?->name;
                    $typeLabel = 'Епізод';
                    $url = $episode ? 'http://localhost:3000/episode/' . $episode->slug : null;
                }

                return [
                    'user_name' => $comment->user->name,
                    'user_avatar' => $comment->user->avatar,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'text' => Str::limit(strip_tags($comment->body), 50),
                    'title' => $title,
                    'url' =>$url,
                    'type' => $typeLabel,
                ];
            });

        // 6. 4 нових аніме (за датою створення)
        $newAnimes = Anime::latest('created_at')
            ->limit(4)
            ->get()
            ->map(fn($anime) => $this->formatAnime($anime));

        // 7. Топ 5 користувачів (за кількістю коментарів або активністю)
        $topUsers = User::withCount(['comments', 'achievements'])
            ->orderByDesc('comments_count')
            ->orderByDesc('achievements_count')
            ->limit(5)
            ->get()
            ->map(function ($user, $index) {
                return [
                    'id' => $user->id,
                    'avatar' => $user->avatar,
                    'name' => $user->name,
                    'created_at' => $user->created_at->toDateString(),
                    'comments_count' => $user->comments_count,
                    'achievements_count' => $user->achievements_count,
                    'rank' => $index + 1,
                ];
            });

        // 8. Календар релізів (6 епізодів найближчим часом)
        $now = Carbon::now();

        $start = $now->copy()->startOfMonth()->startOfDay();
        $end = $now->copy()->endOfMonth()->endOfDay();
        $releaseCalendar = Episode::with('anime')
            ->whereBetween('air_date', [$start, $end])
            ->orderBy('air_date')
            ->limit(6)
            ->get()
            ->map(function ($episode) {
                return [
                    'id' => $episode->id,
                    'anime_id' => $episode->anime->id,
                    'anime_name' => $episode->anime->name,
                    'anime_poster' => $episode->anime->poster,
                    'air_date' => $episode->air_date->format('d.m'),
                    'month' => $episode->air_date->locale('uk')->translatedFormat('F'),
                    'number' => $episode->number,
                    'slug' => $episode->slug,
                ];
            });

        // 9. Топ 4 онгоїнга за imdb_score
        $topOngoings = Anime::where('status', 'ongoing')
            ->orderByDesc('imdb_score')
            ->limit(4)
            ->get()
            ->map(fn($anime) => $this->formatAnime($anime));

        // 10. Рекомендовані (для залогінених)
        if ($user) {
            $favoriteAnimeIds = DB::table('user_lists')
                ->where('user_id', $user->id)
                ->where('listable_type', Anime::class)
                ->where('type', UserListType::FAVORITE->value)
                ->pluck('listable_id')
                ->unique()
                ->toArray();

            if (empty($favoriteAnimeIds)) {
                $recommended = collect();
            } else {
                $similarAnimeIds = Anime::whereIn('id', $favoriteAnimeIds)
                    ->get()
                    ->flatMap(fn($anime) => collect($anime->similars)->pluck('anime_id'))
                    ->unique()
                    ->toArray();

                $recommendedIds = array_diff($similarAnimeIds, $favoriteAnimeIds);

                $recommended = Anime::whereIn('id', $recommendedIds)
                    ->limit(4)
                    ->get()
                    ->map(fn($anime) => $this->formatAnime($anime));
            }
        } else {
            $recommended = collect();
        }

        // 11. Скоро на сайті (5 аніме зі статусом anons)
        $soon = Anime::where('status', 'anons')
            ->orderBy('first_air_date')
            ->limit(5)
            ->get()
            ->map(function ($anime) {
                $count = $anime->seasonsCount();
                return [
                    'id' => $anime->id,
                    'name' => $anime->name,
                    'year' => $anime->first_air_date ? $anime->first_air_date->year : null,
                    'kind' => $anime->kind,
                    'poster' => $anime->poster,
                    'imdb_score' => $anime->imdb_score,
                    'duration' => $anime->duration,
                    'related_seasons_count' => $count + 1,
                ];
            });


        $genres = Tag::with(['animes' => function ($q) {
            $q->select('poster');
        }])
            ->where('is_genre', true)
            ->limit(3)
            ->get(['id', 'name', 'description', 'slug']);

        // 13. 3 теги (не жанри) з описом і аніме
        $tags = Tag::with(['animes' => function ($q) {
            $q->select('poster');
        }])
            ->where('is_genre', false)
            ->limit(3)
            ->get(['id', 'name', 'description','slug']);

        // 14. Останні 3 рецензії
        $latestReviews = Rating::with(['anime:id,name', 'user:id,name'])
            ->latest()
            ->limit(3)
            ->get()
            ->map(function ($rating) {
                return [
                    'user_name' => $rating->user->name,
                    'review_date' => $rating->created_at->toDateString(),
                    'anime_name' => optional($rating->anime)->name,
                    'number' => $rating->number,
                    'review' => $rating->review,
                ];
            });

        return response()->json([
            'five_anime' => $fiveAnime,
            'continue_watching' => $continueWatching,
            'popular_now' => $popularNow,
            'top_10' => $top10,
            'latest_comments' => $latestComments,
            'new_animes' => $newAnimes,
            'top_users' => $topUsers,
            'release_calendar' => $releaseCalendar,
            'top_ongoings' => $topOngoings,
            'recommended' => $recommended,
            'soon' => $soon,
            'genres' => $genres,
            'tags' => $tags,
            'latest_reviews' => $latestReviews,
        ]);
    }
}
