<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property string|null $icon
 * @property int $max_counts
 * @property-read mixed $meta_image
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\User> $users
 * @property-read int|null $users_count
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement byName(string $name)
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement bySlug(string $slug)
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement byType(string $type)
 * @method static \Database\Factories\AchievementFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement hidden()
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement newModelQuery()
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement newQuery()
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement orderByUsersCount(string $direction = 'desc')
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement query()
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement visible()
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement whereDescription($value)
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement whereIcon($value)
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement whereId($value)
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement whereMaxCounts($value)
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement whereName($value)
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement whereSlug($value)
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement withUsers()
 * @method static \AnimeSite\Models\Builders\AchievementQueryBuilder<static>|Achievement withUsersCount()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAchievement {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $achievement_id
 * @property int $progress_count
 * @property-read \AnimeSite\Models\Achievement $achievement
 * @property-read \AnimeSite\Models\User $user
 * @method static \AnimeSite\Models\Builders\AchievementUserQueryBuilder<static>|AchievementUser byAchievement($achievementId)
 * @method static \AnimeSite\Models\Builders\AchievementUserQueryBuilder<static>|AchievementUser byUser($userId)
 * @method static \AnimeSite\Models\Builders\AchievementUserQueryBuilder<static>|AchievementUser completed(int $threshold)
 * @method static \Database\Factories\AchievementUserFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\AchievementUserQueryBuilder<static>|AchievementUser newModelQuery()
 * @method static \AnimeSite\Models\Builders\AchievementUserQueryBuilder<static>|AchievementUser newQuery()
 * @method static \AnimeSite\Models\Builders\AchievementUserQueryBuilder<static>|AchievementUser orderByProgress(string $direction = 'desc')
 * @method static \AnimeSite\Models\Builders\AchievementUserQueryBuilder<static>|AchievementUser query()
 * @method static \AnimeSite\Models\Builders\AchievementUserQueryBuilder<static>|AchievementUser whereAchievementId($value)
 * @method static \AnimeSite\Models\Builders\AchievementUserQueryBuilder<static>|AchievementUser whereId($value)
 * @method static \AnimeSite\Models\Builders\AchievementUserQueryBuilder<static>|AchievementUser whereProgressCount($value)
 * @method static \AnimeSite\Models\Builders\AchievementUserQueryBuilder<static>|AchievementUser whereUserId($value)
 * @method static \AnimeSite\Models\Builders\AchievementUserQueryBuilder<static>|AchievementUser withMinProgress(int $count)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAchievementUser {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property array|null $api_sources
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property array|null $aliases
 * @property string $studio_id
 * @property array $countries
 * @property string|null $poster
 * @property int|null $duration
 * @property-read int|null $episodes_count
 * @property \Illuminate\Support\Carbon|null $first_air_date
 * @property \Illuminate\Support\Carbon|null $last_air_date
 * @property float|null $imdb_score
 * @property array $attachments
 * @property array|null $related
 * @property array|null $similars
 * @property bool $is_published
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \AnimeSite\Enums\Kind $kind
 * @property \AnimeSite\Enums\Status $status
 * @property \AnimeSite\Enums\Period|null $period
 * @property \AnimeSite\Enums\RestrictedRating $restricted_rating
 * @property \AnimeSite\Enums\Source $source
 * @property string|null $searchable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Episode> $episodes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Person> $people
 * @property-read int|null $people_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Rating> $ratings
 * @property-read int|null $ratings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Selection> $selections
 * @property-read int|null $selections_count
 * @property-read \AnimeSite\Models\Studio $studio
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $userLists
 * @property-read int|null $user_lists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\WatchHistory> $watchHistories
 * @property-read int|null $watch_histories_count
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime bySlug(string $slug)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime fromCountries(array $countryCodes)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime newModelQuery()
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime newQuery()
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime ofKind(\AnimeSite\Enums\Kind $kind)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime popular()
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime query()
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime recentlyAdded(int $limit = 10)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime releasedInYear(int $year)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime search(string $search, array $fields = [], float $trigramThreshold = '0.5')
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime trending(int $days = 7)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereAliases($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereApiSources($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereAttachments($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereCountries($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereDescription($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereDuration($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereEpisodesCount($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereFirstAirDate($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereId($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereImdbScore($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereIsPublished($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereKind($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereLastAirDate($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereMetaDescription($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereMetaImage($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereMetaTitle($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereName($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime wherePeriod($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime wherePoster($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereRelated($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereRestrictedRating($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereSearchable($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereSimilars($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereSlug($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereSource($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereStatus($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereStudioId($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime withAverageRating()
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime withImdbScoreGreaterThan(float $score)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime withPersons(array $personIds)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime withStatus(\AnimeSite\Enums\Status $status)
 * @method static \AnimeSite\Models\Builders\AnimeQueryBuilder<static>|Anime withTags(array $tagIds)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAnime {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $commentable_type
 * @property string $commentable_id
 * @property string $user_id
 * @property bool $is_spoiler
 * @property bool $is_approved
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $anime
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Comment> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $episode
 * @property-read string $translated_type
 * @property-read mixed $is_reply
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\CommentLike> $likes
 * @property-read int|null $likes_count
 * @property-read Comment|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\CommentReport> $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $selection
 * @property-read \AnimeSite\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $userLists
 * @property-read int|null $user_lists_count
 * @method static \Database\Factories\CommentFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment forCommentable(string $commentableType, string $commentableId)
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment forUser(string $userId)
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment mostLiked(int $limit = 10)
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment newModelQuery()
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment newQuery()
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment query()
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment replies()
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment roots()
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment whereBody($value)
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment whereCommentableId($value)
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment whereCommentableType($value)
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment whereId($value)
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment whereIsApproved($value)
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment whereIsSpoiler($value)
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment whereUserId($value)
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment withSpoilers()
 * @method static \AnimeSite\Models\Builders\CommentQueryBuilder<static>|Comment withoutSpoilers()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperComment {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $comment_id
 * @property string $user_id
 * @property bool $is_liked
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \AnimeSite\Models\Comment $comment
 * @property-read \AnimeSite\Models\User $user
 * @method static \AnimeSite\Models\Builders\CommentLikeQueryBuilder<static>|CommentLike byComment(string $commentId)
 * @method static \AnimeSite\Models\Builders\CommentLikeQueryBuilder<static>|CommentLike byUser(string $userId)
 * @method static \Database\Factories\CommentLikeFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\CommentLikeQueryBuilder<static>|CommentLike newModelQuery()
 * @method static \AnimeSite\Models\Builders\CommentLikeQueryBuilder<static>|CommentLike newQuery()
 * @method static \AnimeSite\Models\Builders\CommentLikeQueryBuilder<static>|CommentLike onlyDislikes()
 * @method static \AnimeSite\Models\Builders\CommentLikeQueryBuilder<static>|CommentLike onlyLikes()
 * @method static \AnimeSite\Models\Builders\CommentLikeQueryBuilder<static>|CommentLike query()
 * @method static \AnimeSite\Models\Builders\CommentLikeQueryBuilder<static>|CommentLike whereCommentId($value)
 * @method static \AnimeSite\Models\Builders\CommentLikeQueryBuilder<static>|CommentLike whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\CommentLikeQueryBuilder<static>|CommentLike whereId($value)
 * @method static \AnimeSite\Models\Builders\CommentLikeQueryBuilder<static>|CommentLike whereIsLiked($value)
 * @method static \AnimeSite\Models\Builders\CommentLikeQueryBuilder<static>|CommentLike whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\CommentLikeQueryBuilder<static>|CommentLike whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCommentLike {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $comment_id
 * @property string $user_id
 * @property bool $is_viewed
 * @property string|null $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \AnimeSite\Enums\CommentReportType $type
 * @property-read \AnimeSite\Models\Comment $comment
 * @property-read \AnimeSite\Models\User $user
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport byComment(string $commentId)
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport byType(\AnimeSite\Enums\CommentReportType $type)
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport byUser(string $userId)
 * @method static \Database\Factories\CommentReportFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport newModelQuery()
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport newQuery()
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport query()
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport unViewed()
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport viewed()
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport whereBody($value)
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport whereCommentId($value)
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport whereId($value)
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport whereIsViewed($value)
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport whereType($value)
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\CommentReportQueryBuilder<static>|CommentReport whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCommentReport {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $anime_id
 * @property int $number
 * @property string $slug
 * @property string $name
 * @property string|null $description
 * @property int|null $duration
 * @property \Illuminate\Support\Carbon|null $air_date
 * @property bool $is_filler
 * @property array|null $pictures
 * @property array $video_players
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\WatchParty> $activeWatchParties
 * @property-read int|null $active_watch_parties_count
 * @property-read \AnimeSite\Models\Anime $anime
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $formatted_duration
 * @property-read mixed $full_name
 * @property-read mixed $meta_image_url
 * @property-read mixed $picture_url
 * @property-read mixed $pictures_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Selection> $selections
 * @property-read int|null $selections_count
 * @property-write mixed $file_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $userLists
 * @property-read int|null $user_lists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\WatchHistory> $watchHistories
 * @property-read int|null $watch_histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\WatchParty> $watchParties
 * @property-read int|null $watch_parties_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\User> $watchedByUsers
 * @property-read int|null $watched_by_users_count
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode airedAfter(\Carbon\Carbon $date)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode bySlug(string $slug)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode chaperone()
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode fillers(bool $includeFiller = false)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode forAnime(string $animeId)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode newModelQuery()
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode newQuery()
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode orderByNumber(string $direction = 'asc')
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode query()
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode recentlyAired(int $days = 7)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereAirDate($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereAnimeId($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereDescription($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereDuration($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereId($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereIsFiller($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereMetaDescription($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereMetaImage($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereMetaTitle($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereName($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereNumber($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode wherePictures($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereSlug($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\EpisodeQueryBuilder<static>|Episode whereVideoPlayers($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperEpisode {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $tariff_id
 * @property numeric $amount
 * @property string $currency
 * @property string $payment_method
 * @property string $transaction_id
 * @property array|null $liqpay_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \AnimeSite\Enums\PaymentStatus $status
 * @property-read \AnimeSite\Models\Tariff $tariff
 * @property-read \AnimeSite\Models\User $user
 * @method static \Database\Factories\PaymentFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment failed()
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment forSubscription(string $subscriptionId)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment forUser(string $userId)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment inDateRange(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment newModelQuery()
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment newQuery()
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment pending()
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment query()
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment successful()
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment whereAmount($value)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment whereCurrency($value)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment whereId($value)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment whereLiqpayData($value)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment wherePaymentMethod($value)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment whereStatus($value)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment whereTariffId($value)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment whereTransactionId($value)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment whereUserId($value)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment withAmountGreaterThan(float $amount)
 * @method static \AnimeSite\Models\Builders\PaymentQueryBuilder<static>|Payment withStatus(\AnimeSite\Enums\PaymentStatus $status)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPayment {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $slug
 * @property string $name
 * @property string|null $original_name
 * @property string|null $image
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $birthday
 * @property string|null $birthplace
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \AnimeSite\Enums\PersonType $type
 * @property \AnimeSite\Enums\Gender|null $gender
 * @property string|null $searchable
 * @property-read mixed $age
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Anime> $animes
 * @property-read int|null $animes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Selection> $selections
 * @property-read int|null $selections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $userLists
 * @property-read int|null $user_lists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Person> $voiceActor
 * @property-read int|null $voice_actor_count
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person byGender(\AnimeSite\Enums\Gender|string $gender)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person byName(string $name)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person bySlug(string $slug)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person byType(\AnimeSite\Enums\PersonType $type)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person characters()
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person directors()
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person newModelQuery()
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person newQuery()
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person orderByAnimeCount(string $direction = 'desc')
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person query()
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person scriptwriters()
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person search(string $search, array $fields = [], float $trigramThreshold = '0.5')
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person voiceActors()
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereBirthday($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereBirthplace($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereDescription($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereGender($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereId($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereImage($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereMetaDescription($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereMetaImage($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereMetaTitle($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereName($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereOriginalName($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereSearchable($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereSlug($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereType($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person withAnimeCount()
 * @method static \AnimeSite\Models\Builders\PersonQueryBuilder<static>|Person withAnimes()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPerson {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $anime_id
 * @property int $number
 * @property mixed|null $review
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \AnimeSite\Models\Anime $anime
 * @property-read \AnimeSite\Models\User $user
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating betweenRatings(int $minRating, int $maxRating)
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating forAnime(string $animeId)
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating forUser(string $userId)
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating highRatings(int $threshold = 8)
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating lowRatings(int $threshold = 4)
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating newModelQuery()
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating newQuery()
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating query()
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating whereAnimeId($value)
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating whereId($value)
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating whereNumber($value)
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating whereReview($value)
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating whereUserId($value)
 * @method static \AnimeSite\Models\Builders\RatingQueryBuilder<static>|Rating withReviews()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRating {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $query
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \AnimeSite\Models\User $user
 * @method static \AnimeSite\Models\Builders\SearchHistoryQueryBuilder<static>|SearchHistory byUser($userId)
 * @method static \Database\Factories\SearchHistoryFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\SearchHistoryQueryBuilder<static>|SearchHistory fromLastDays(int $days = 30)
 * @method static \AnimeSite\Models\Builders\SearchHistoryQueryBuilder<static>|SearchHistory latestFirst()
 * @method static \AnimeSite\Models\Builders\SearchHistoryQueryBuilder<static>|SearchHistory newModelQuery()
 * @method static \AnimeSite\Models\Builders\SearchHistoryQueryBuilder<static>|SearchHistory newQuery()
 * @method static \AnimeSite\Models\Builders\SearchHistoryQueryBuilder<static>|SearchHistory oldestFirst()
 * @method static \AnimeSite\Models\Builders\SearchHistoryQueryBuilder<static>|SearchHistory query()
 * @method static \AnimeSite\Models\Builders\SearchHistoryQueryBuilder<static>|SearchHistory whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\SearchHistoryQueryBuilder<static>|SearchHistory whereId($value)
 * @method static \AnimeSite\Models\Builders\SearchHistoryQueryBuilder<static>|SearchHistory whereQuery($value)
 * @method static \AnimeSite\Models\Builders\SearchHistoryQueryBuilder<static>|SearchHistory whereQueryLike(string $keyword)
 * @method static \AnimeSite\Models\Builders\SearchHistoryQueryBuilder<static>|SearchHistory whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\SearchHistoryQueryBuilder<static>|SearchHistory whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSearchHistory {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $slug
 * @property string $name
 * @property string|null $description
 * @property bool $is_published
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $searchable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Anime> $animes
 * @property-read int|null $animes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Episode> $episodes
 * @property-read int|null $episodes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Person> $persons
 * @property-read int|null $persons_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \AnimeSite\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $userLists
 * @property-read int|null $user_lists_count
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection bySlug(string $slug)
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection byUser(string $userId)
 * @method static \Database\Factories\SelectionFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection newModelQuery()
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection newQuery()
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection orderByAnimeCount(string $direction = 'desc')
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection published()
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection query()
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection search(string $search, array $fields = [], float $trigramThreshold = '0.5')
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection unpublished()
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection whereDescription($value)
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection whereId($value)
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection whereIsPublished($value)
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection whereMetaDescription($value)
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection whereMetaImage($value)
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection whereMetaTitle($value)
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection whereName($value)
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection whereSearchable($value)
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection whereSlug($value)
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection whereUserId($value)
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection withAnimeCount()
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection withAnimes()
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection withComments()
 * @method static \AnimeSite\Models\Builders\SelectionQueryBuilder<static>|Selection withPersons()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSelection {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property string|null $image
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $searchable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Anime> $animes
 * @property-read int|null $animes_count
 * @property-read string|null $image_url
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio byName(string $name)
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio bySlug(string $slug)
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio newModelQuery()
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio newQuery()
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio orderByAnimeCount(string $direction = 'desc')
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio query()
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio search(string $search, array $fields = [], float $trigramThreshold = '0.5')
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio whereDescription($value)
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio whereId($value)
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio whereImage($value)
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio whereMetaDescription($value)
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio whereMetaImage($value)
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio whereMetaTitle($value)
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio whereName($value)
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio whereSearchable($value)
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio whereSlug($value)
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio withAnimeCount()
 * @method static \AnimeSite\Models\Builders\StudioQueryBuilder<static>|Studio withAnimes()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperStudio {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property string|null $image
 * @property array $aliases
 * @property bool $is_genre
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $parent_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Anime> $animes
 * @property-read int|null $animes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Tag> $children
 * @property-read int|null $children_count
 * @property-read int|null $people_count
 * @property-read Tag|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Person> $people
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Selection> $selections
 * @property-read int|null $selections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $userLists
 * @property-read int|null $user_lists_count
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag bySlug(string $slug)
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag genres()
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag newModelQuery()
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag newQuery()
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag nonGenres()
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag popular()
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag query()
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag search(string $term)
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag whereAliases($value)
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag whereDescription($value)
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag whereId($value)
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag whereImage($value)
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag whereIsGenre($value)
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag whereMetaDescription($value)
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag whereMetaImage($value)
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag whereMetaTitle($value)
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag whereName($value)
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag whereParentId($value)
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag whereSlug($value)
 * @method static \AnimeSite\Models\Builders\TagQueryBuilder<static>|Tag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTag {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property numeric $price
 * @property string $currency
 * @property int $duration_days
 * @property array $features
 * @property bool $is_active
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $formatted_price
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserSubscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff active()
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff bySlug(string $slug)
 * @method static \Database\Factories\TariffFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff inactive()
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff newModelQuery()
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff newQuery()
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff orderByDuration(string $direction = 'asc')
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff orderByPrice(string $direction = 'asc')
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff query()
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff whereCurrency($value)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff whereDescription($value)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff whereDurationDays($value)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff whereFeatures($value)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff whereId($value)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff whereIsActive($value)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff whereMetaDescription($value)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff whereMetaImage($value)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff whereMetaTitle($value)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff whereName($value)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff wherePrice($value)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff whereSlug($value)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff withDurationMonths(int $months)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff withPriceBetween(float $minPrice, float $maxPrice)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff withPriceGreaterThan(float $price)
 * @method static \AnimeSite\Models\Builders\TariffQueryBuilder<static>|Tariff withPriceLessThan(float $price)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTariff {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \AnimeSite\Enums\Role $role
 * @property \AnimeSite\Enums\Gender|null $gender
 * @property string $id
 * @property string|null $avatar
 * @property string|null $backdrop
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $birthday
 * @property bool $allow_adult
 * @property \Illuminate\Support\Carbon|null $last_seen_at
 * @property bool $is_auto_next
 * @property bool $is_auto_play
 * @property bool $is_auto_skip_intro
 * @property bool $is_private_favorites
 * @property bool $is_banned
 * @property bool $notify_new_episodes
 * @property bool $notify_episode_date_changes
 * @property bool $notify_announcement_to_ongoing
 * @property bool $notify_comment_replies
 * @property bool $notify_comment_likes
 * @property bool $notify_review_replies
 * @property bool $notify_planned_reminders
 * @property bool $notify_new_selections
 * @property bool $notify_status_changes
 * @property bool $notify_new_seasons
 * @property bool $notify_subscription_expiration
 * @property bool $notify_subscription_renewal
 * @property bool $notify_payment_issues
 * @property bool $notify_tariff_changes
 * @property bool $notify_site_updates
 * @property bool $notify_maintenance
 * @property bool $notify_security_changes
 * @property bool $notify_new_features
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Achievement> $achievements
 * @property-read int|null $achievements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\AchievementUser> $achievementsPivot
 * @property-read int|null $achievements_pivot_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Anime> $animeNotifications
 * @property-read int|null $anime_notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\CommentLike> $commentLikes
 * @property-read int|null $comment_likes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\CommentReport> $commentReports
 * @property-read int|null $comment_reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $favoriteAnimes
 * @property-read int|null $favorite_animes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $favoriteAnimesPreview
 * @property-read int|null $favorite_animes_preview_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $favoriteEpisodes
 * @property-read int|null $favorite_episodes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $favoritePeople
 * @property-read int|null $favorite_people_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $favoritePeoplePreview
 * @property-read int|null $favorite_people_preview_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $favoriteSelections
 * @property-read int|null $favorite_selections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $favoriteTags
 * @property-read int|null $favorite_tags_count
 * @property-read mixed $age
 * @property-read string|null $formatted_last_seen
 * @property-read bool $is_online
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\WatchParty> $hostedWatchParties
 * @property-read int|null $hosted_watch_parties_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\WatchParty> $participatedWatchParties
 * @property-read int|null $participated_watch_parties_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $plannedAnimes
 * @property-read int|null $planned_animes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Rating> $ratings
 * @property-read int|null $ratings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $reWatchingAnimes
 * @property-read int|null $re_watching_animes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\SearchHistory> $searchHistories
 * @property-read int|null $search_histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Selection> $selections
 * @property-read int|null $selections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $stoppedAnimes
 * @property-read int|null $stopped_animes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserSubscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $userLists
 * @property-read int|null $user_lists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\WatchHistory> $watchHistories
 * @property-read int|null $watch_histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\WatchPartyMessage> $watchPartyMessages
 * @property-read int|null $watch_party_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $watchedAnimes
 * @property-read int|null $watched_animes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\Episode> $watchedEpisodes
 * @property-read int|null $watched_episodes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\UserList> $watchingAnimes
 * @property-read int|null $watching_animes_count
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User active(int $days = 30)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User admins()
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User allowedAdults()
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User banned()
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User byAgeRange(int $minAge, int $maxAge)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User byRole(\AnimeSite\Enums\Role $role)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User inactive(int $days = 30)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User moderators()
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User newModelQuery()
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User newQuery()
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User notBanned()
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User query()
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereAllowAdult($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereAvatar($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereBackdrop($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereBirthday($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereDescription($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereEmail($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereEmailVerifiedAt($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereGender($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereId($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereIsAutoNext($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereIsAutoPlay($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereIsAutoSkipIntro($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereIsBanned($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereIsPrivateFavorites($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereLastSeenAt($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereName($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifyAnnouncementToOngoing($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifyCommentLikes($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifyCommentReplies($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifyEpisodeDateChanges($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifyMaintenance($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifyNewEpisodes($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifyNewFeatures($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifyNewSeasons($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifyNewSelections($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifyPaymentIssues($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifyPlannedReminders($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifyReviewReplies($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifySecurityChanges($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifySiteUpdates($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifyStatusChanges($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifySubscriptionExpiration($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifySubscriptionRenewal($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereNotifyTariffChanges($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User wherePassword($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereRememberToken($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereRole($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User withActiveSubscriptions()
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User withAutoRenewableSubscriptions()
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User withExpiredSubscriptions()
 * @method static \AnimeSite\Models\Builders\UserQueryBuilder<static>|User withSettings(?bool $autoNext = null, ?bool $autoPlay = null, ?bool $autoSkipIntro = null)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $listable_type
 * @property string $listable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \AnimeSite\Enums\UserListType $type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $listable
 * @property-read \AnimeSite\Models\User $user
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList excludeTypes(array $types)
 * @method static \Database\Factories\UserListFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList favorites()
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList forListable(string $listableType, string $listableId)
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList forListableType(string $listableType)
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList forUser(string $userId, ?string $listableClass = null, ?\AnimeSite\Enums\UserListType $userListType = null)
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList newModelQuery()
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList newQuery()
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList ofType(\AnimeSite\Enums\UserListType $type)
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList planned()
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList query()
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList rewatching()
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList stopped()
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList watched()
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList watching()
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList whereId($value)
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList whereListableId($value)
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList whereListableType($value)
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList whereType($value)
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\UserListQueryBuilder<static>|UserList whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUserList {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $tariff_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property bool $is_active
 * @property bool $auto_renew
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \AnimeSite\Models\Tariff $tariff
 * @property-read \AnimeSite\Models\User $user
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription active()
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription autoRenewable()
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription expired()
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription expiringSoon(int $days = 7)
 * @method static \Database\Factories\UserSubscriptionFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription forTariff(string $tariffId)
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription forUser(string $userId)
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription inactive()
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription newModelQuery()
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription newQuery()
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription nonAutoRenewable()
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription query()
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription whereAutoRenew($value)
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription whereEndDate($value)
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription whereId($value)
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription whereIsActive($value)
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription whereStartDate($value)
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription whereTariffId($value)
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\UserSubscriptionQueryBuilder<static>|UserSubscription whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUserSubscription {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property string|null $image
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $searchable
 * @property-read string|null $image_url
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam byName(string $name)
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam bySlug(string $slug)
 * @method static \Database\Factories\VoiceoverTeamFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam newModelQuery()
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam newQuery()
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam query()
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam search(string $search, array $fields = [], float $trigramThreshold = '0.5')
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam whereDescription($value)
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam whereId($value)
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam whereImage($value)
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam whereMetaDescription($value)
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam whereMetaImage($value)
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam whereMetaTitle($value)
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam whereName($value)
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam whereSearchable($value)
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam whereSlug($value)
 * @method static \AnimeSite\Models\Builders\VoiceoverTeamBuilder<static>|VoiceoverTeam whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperVoiceoverTeam {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $episode_id
 * @property int $progress_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \AnimeSite\Models\Episode $episode
 * @property-read \AnimeSite\Models\User $user
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory byEpisode($episodeId)
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory byUser($userId)
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory deleteOld(int $days = 30)
 * @method static \Database\Factories\WatchHistoryFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory fromLastDays(int $days)
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory latestFirst()
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory newModelQuery()
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory newQuery()
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory oldestFirst()
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory query()
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory whereEpisodeId($value)
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory whereId($value)
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory whereProgressTime($value)
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory whereUserId($value)
 * @method static \AnimeSite\Models\Builders\WatchHistoryQueryBuilder<static>|WatchHistory withEpisode()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperWatchHistory {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $user_id
 * @property string $episode_id
 * @property bool $is_private
 * @property string|null $password
 * @property int $max_viewers
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \AnimeSite\Enums\WatchPartyStatus $watch_party_status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\User> $activeViewers
 * @property-read int|null $active_viewers_count
 * @property-read \AnimeSite\Models\Episode $episode
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\WatchPartyMessage> $messages
 * @property-read int|null $messages_count
 * @property-read mixed $meta_image
 * @property-read \AnimeSite\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AnimeSite\Models\User> $viewers
 * @property-read int|null $viewers_count
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty active()
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty bySlug(string $slug)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty byUserId(string $userId)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty ended()
 * @method static \Database\Factories\WatchPartyFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty newModelQuery()
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty newQuery()
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty private()
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty public()
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty query()
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty startedWithinMinutes(int $minutes)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty waiting()
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty whereEndedAt($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty whereEpisodeId($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty whereId($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty whereIsPrivate($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty whereMaxViewers($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty whereName($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty whereNameLike(string $name)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty wherePassword($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty whereSlug($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty whereStartedAt($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty whereStatus(\AnimeSite\Enums\WatchPartyStatus $status)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty whereUserId($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyQueryBuilder<static>|WatchParty whereWatchPartyStatus($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperWatchParty {}
}

namespace AnimeSite\Models{
/**
 * 
 *
 * @property string $id
 * @property string $watch_party_id
 * @property string $user_id
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $formatted_time
 * @property-read bool $is_from_host
 * @property-read \AnimeSite\Models\User $user
 * @property-read \AnimeSite\Models\WatchParty $watchParty
 * @method static \Database\Factories\WatchPartyMessageFactory factory($count = null, $state = [])
 * @method static \AnimeSite\Models\Builders\WatchPartyMessageQueryBuilder<static>|WatchPartyMessage forWatchParty(string $watchPartyId)
 * @method static \AnimeSite\Models\Builders\WatchPartyMessageQueryBuilder<static>|WatchPartyMessage fromUser(string $userId)
 * @method static \AnimeSite\Models\Builders\WatchPartyMessageQueryBuilder<static>|WatchPartyMessage newModelQuery()
 * @method static \AnimeSite\Models\Builders\WatchPartyMessageQueryBuilder<static>|WatchPartyMessage newQuery()
 * @method static \AnimeSite\Models\Builders\WatchPartyMessageQueryBuilder<static>|WatchPartyMessage ordered()
 * @method static \AnimeSite\Models\Builders\WatchPartyMessageQueryBuilder<static>|WatchPartyMessage query()
 * @method static \AnimeSite\Models\Builders\WatchPartyMessageQueryBuilder<static>|WatchPartyMessage recent(int $minutes = 60)
 * @method static \AnimeSite\Models\Builders\WatchPartyMessageQueryBuilder<static>|WatchPartyMessage whereCreatedAt($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyMessageQueryBuilder<static>|WatchPartyMessage whereId($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyMessageQueryBuilder<static>|WatchPartyMessage whereMessage($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyMessageQueryBuilder<static>|WatchPartyMessage whereUpdatedAt($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyMessageQueryBuilder<static>|WatchPartyMessage whereUserId($value)
 * @method static \AnimeSite\Models\Builders\WatchPartyMessageQueryBuilder<static>|WatchPartyMessage whereWatchPartyId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperWatchPartyMessage {}
}

