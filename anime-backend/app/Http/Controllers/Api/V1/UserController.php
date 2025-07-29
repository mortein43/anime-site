<?php

namespace AnimeSite\Http\Controllers\Api\V1;

use AnimeSite\DTOs\Users\UserIndexDTO;
use AnimeSite\DTOs\Users\UserSettingsDTO;
use AnimeSite\DTOs\Users\UserUpdateDTO;
use AnimeSite\Http\Requests\Users\UserBanRequest;
use AnimeSite\Http\Requests\Users\UserDeleteRequest;
use AnimeSite\Http\Requests\Users\UserIndexRequest;
use AnimeSite\Http\Requests\Users\UserStoreRequest;
use AnimeSite\Http\Requests\Users\UserUpdateRequest;
use AnimeSite\Http\Resources\AchievementUserResource;
use AnimeSite\Http\Resources\UserCommentResource;
use AnimeSite\Http\Resources\UserProfileResource;
use AnimeSite\Http\Resources\UserRatingResource;
use AnimeSite\Http\Resources\UserSettingsResource;
use AnimeSite\Http\Resources\UserUserListResource;
use AnimeSite\Http\Resources\UserUserSubscriptionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use AnimeSite\Actions\Users\CreateUser;
use AnimeSite\Actions\Users\DeleteUser;
use AnimeSite\Actions\Users\GetAllUsers;
use AnimeSite\Actions\Users\GetUserProfile;
use AnimeSite\Actions\Users\GetUserSettings;
use AnimeSite\Actions\Users\ShowUser;
use AnimeSite\Actions\Users\UpdateUser;
use AnimeSite\Actions\Users\UpdateUserProfile;
use AnimeSite\Actions\Users\UpdateUserSettings;
use AnimeSite\Http\Controllers\Controller;
use AnimeSite\Actions\Users\UploadUserAvatar;
use AnimeSite\Actions\Users\UploadUserBackdrop;
use AnimeSite\Http\Resources\UserResource;
use AnimeSite\Models\User;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    /**
     * Get paginated list of users with filtering, sorting and pagination
     *
     * @param  UserIndexRequest  $request
     * @param  GetAllUsers  $action
     * @return AnonymousResourceCollection
     * @authenticated
     */
    public function index(UserIndexRequest $request, GetAllUsers $action): AnonymousResourceCollection
    {
        $dto = UserIndexDTO::fromRequest($request);
        $users = $action->handle($dto);

        return UserResource::collection($users);
    }

    /**
     * Get detailed information about a specific user
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        // Load basic relationships first
        $user->load([
            'userLists.listable',
            'ratings',
            'comments',
            'subscriptions',
            'achievements',
        ])
            ->loadCount([
                'userLists',
                'ratings',
                'comments',
                'subscriptions',
                'achievements',
                'watchingAnimes as watching_animes_count',
                'plannedAnimes as planned_animes_count',
                'watchedAnimes as watched_animes_count',
                'stoppedAnimes as stopped_animes_count',
                'reWatchingAnimes as re_watching_animes_count'
            ]);

        // Load favorites separately with model-specific fields
        $user->load([
            'favoriteAnimesPreview' => function ($query) {
                $query->where('listable_type', \AnimeSite\Models\Anime::class)
                    ->with('listable:id,name,poster,first_air_date,kind');
            },
            'favoritePeoplePreview' => function ($query) {
                $query->where('listable_type', \AnimeSite\Models\Person::class)
                    ->with('listable:id,name,image,birthday,type');
            }
        ]);

        return new UserResource($user);
    }

    /**
     * Update the specified user
     *
     * @param  UserUpdateRequest  $request
     * @param  User  $user
     * @param UpdateUser $action
     * @return UserResource
     * @authenticated
     */
    public function update(UserUpdateRequest $request, User $user, UpdateUser $action): UserResource
    {
        $dto = UserUpdateDTO::fromRequest($request);
        $user = $action->handle($user, $dto);

        return new UserResource($user);
    }

    /**
     * Partially update the specified user
     *
     * @param  UserUpdateRequest  $request
     * @param  UpdateUser  $action
     * @return UserResource
     * @authenticated
     */
    public function updatePartial(UserUpdateRequest $request, UpdateUser $action): UserResource
    {
        $user = $request->user();
        $dto = UserUpdateDTO::fromRequest($request);
        logger($request->validated());
        $updatedUser = $action->handle($user, $dto);

        return new UserResource($updatedUser);
    }

    /**
     * Store a newly created user
     *
     * @param  UserStoreRequest  $request
     * @param  CreateUser $action
     * @return UserResource
     * @authenticated
     */
    public function store(UserStoreRequest $request, CreateUser $action): UserResource
    {
        $dto = UserUpdateDTO::fromRequest($request);
        $user = $action->handle($dto);

        return new UserResource($user);
    }

    /**
     * Remove the specified user
     *
     * @param  UserDeleteRequest  $request
     * @param  User  $user
     * @return JsonResponse
     * @authenticated
     */
    public function destroy(UserDeleteRequest $request, User $user): JsonResponse
    {
        // Перевірка, чи користувач намагається видалити себе
        if ($request->user()->id === $user->id) {
            return response()->json([
                'message' => 'Cannot delete your own account'
            ], 403);
        }

        // Перевірка, чи користувач намагається видалити адміністратора
        if ($user->isAdmin()) {
            return response()->json([
                'message' => 'Cannot delete an admin user'
            ], 403);
        }

        // Перевірка, чи є у користувача пов'язані дані
        // Тут можна додати перевірки для інших пов'язаних даних, якщо потрібно
        // Наприклад, перевірка наявності коментарів, рейтингів тощо
        if ($user->comments()->exists()) {
            return response()->json([
                'message' => 'Cannot delete user with comments. Delete comments first.'
            ], 422);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function profile(): UserProfileResource
    {
        $user = auth()->user();

        $user->loadCount([
            'watchingAnimes',
            'plannedAnimes',
            'watchedAnimes',
            'stoppedAnimes',
            'reWatchingAnimes',
            'ratings',
            'comments',
            'subscriptions',
            'achievements',
        ])
            ->load([
                'favoriteAnimesPreview.listable',
                'favoritePeoplePreview.listable',
                'watchHistories' => fn ($query) => $query->latest()->with('episode.anime')->limit(3),
            ]);

        return new UserProfileResource($user);
    }

    public function settings(): UserSettingsResource
    {
        //$user = auth()->user();
//        dd(Auth::user());
        return new UserSettingsResource(Auth::user());
    }

    /**
     * Ban the specified user
     *
     * @param  UserBanRequest  $request
     * @param  User  $user
     * @param  UpdateUser  $action
     * @return UserResource
     * @authenticated
     */
    public function ban(UserBanRequest $request, User $user, UpdateUser $action): UserResource|JsonResponse
    {
        // Перевірка, чи користувач намагається заблокувати себе
        if ($request->user()->id === $user->id) {
            return response()->json([
                'message' => 'Cannot ban yourself'
            ], 403);
        }

        // Перевірка, чи користувач намагається заблокувати адміністратора
        if ($user->isAdmin()) {
            return response()->json([
                'message' => 'Cannot ban an admin user'
            ], 403);
        }

        $request->merge(['is_banned' => true]);
        $dto = UserUpdateDTO::fromRequest($request);
        $user = $action->handle($user, $dto);

        return new UserResource($user);
    }

    /**
     * Unban the specified user
     *
     * @param  UserBanRequest  $request
     * @param  User  $user
     * @param  UpdateUser  $action
     * @return UserResource
     * @authenticated
     */
    public function unban(UserBanRequest $request, string $id, UpdateUser $action): UserResource
    {
        // Отримуємо користувача без застосування BannedScope
        $user = User::withoutGlobalScope('AnimeSite\Models\Scopes\BannedScope')->find($id);

        if (!$user) {
            abort(404, 'User not found');
        }

        $request->merge(['is_banned' => false]);
        $dto = UserUpdateDTO::fromRequest($request);
        $user = $action->handle($user, $dto);

        return new UserResource($user);
    }

    /**
     * Get user lists for a specific user
     *
     * @param  User  $user
     * @return AnonymousResourceCollection
     */
    public function userLists(User $user): AnonymousResourceCollection
    {
        $userLists = $user->userLists()->paginate();

        return UserUserListResource::collection($userLists);
    }

    /**
     * Get ratings for a specific user
     *
     * @param  User  $user
     * @return AnonymousResourceCollection
     */
    public function ratings(User $user): AnonymousResourceCollection
    {
        $ratings = $user->ratings()->paginate();

        return UserRatingResource::collection($ratings);
    }

    /**
     * Get comments for a specific user
     *
     * @param  User  $user
     * @return AnonymousResourceCollection
     */
    public function comments(User $user): AnonymousResourceCollection
    {
        $comments = $user->comments()->paginate();

        return UserCommentResource::collection($comments);
    }
    /**
     * Get comments for a specific user
     *
     * @param  User  $user
     * @return AnonymousResourceCollection
     */
    public function achievements(User $user): AnonymousResourceCollection
    {
        $achievements = $user->achievements()->paginate();

        return AchievementUserResource::collection($achievements);
    }

    /**
     * Get subscriptions for a specific user
     *
     * @param  User  $user
     * @return AnonymousResourceCollection
     * @authenticated
     */
    public function subscriptions(User $user): AnonymousResourceCollection
    {
        $subscriptions = $user->subscriptions()->paginate();

        return UserUserSubscriptionResource::collection($subscriptions);
    }
}
