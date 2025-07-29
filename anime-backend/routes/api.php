<?php

use AnimeSite\Http\Controllers\Api\V1\Auth\AuthenticatedSessionController;
use AnimeSite\Http\Controllers\Api\V1\Auth\EmailVerificationNotificationController;
use AnimeSite\Http\Controllers\Api\V1\Auth\GoogleAuthController;
use AnimeSite\Http\Controllers\Api\V1\Auth\NewPasswordController;
use AnimeSite\Http\Controllers\Api\V1\Auth\PasswordResetLinkController;
use AnimeSite\Http\Controllers\Api\V1\Auth\RegisteredUserController;
use AnimeSite\Http\Controllers\Api\V1\Auth\VerifyEmailController;
use AnimeSite\Http\Controllers\Api\V1\CommentLikeController;
use AnimeSite\Http\Controllers\Api\V1\CommentReportController;
use AnimeSite\Http\Controllers\Api\V1\HomeController;
use AnimeSite\Http\Controllers\Api\V1\WatchPartyController;
use AnimeSite\Http\Controllers\Api\V1\WatchPartyMessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use AnimeSite\Http\Controllers\Api\V1\AnimeController;
use AnimeSite\Http\Controllers\Api\V1\CommentController;
use AnimeSite\Http\Controllers\Api\V1\EpisodeController;
use AnimeSite\Http\Controllers\Api\V1\PersonController;
use AnimeSite\Http\Controllers\Api\V1\RatingController;
use AnimeSite\Http\Controllers\Api\V1\SearchController;
use AnimeSite\Http\Controllers\Api\V1\SelectionController;
use AnimeSite\Http\Controllers\Api\V1\StudioController;
use AnimeSite\Http\Controllers\Api\V1\TagController;
use AnimeSite\Http\Controllers\Api\V1\UserController;
use AnimeSite\Http\Controllers\Api\V1\UserListController;
use AnimeSite\Http\Controllers\Api\V1\WatchHistoryController;
use AnimeSite\Http\Controllers\Api\V1\AchievementController;
use AnimeSite\Http\Controllers\Api\V1\AuthController;
use AnimeSite\Http\Controllers\Api\V1\RecommendationController;
use AnimeSite\Http\Controllers\Api\V1\NotificationController;
use AnimeSite\Http\Controllers\Api\V1\TariffController;
use AnimeSite\Http\Controllers\Api\V1\UserSubscriptionController;
use AnimeSite\Http\Controllers\Api\V1\PaymentController;
use AnimeSite\Http\Controllers\Api\TestController;
use Illuminate\Support\Facades\Storage;


// API Routes (v1)
Route::group(['prefix' => 'v1'], function () {
    // Authentication routes
    Route::group(['prefix' => 'auth'], function () {
//        Route::options('{any}', function () {
//            return response('', 200)
//                ->header('Access-Control-Allow-Origin', '*')
//                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
//                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
//        })->where('any', '.*');

        Route::post('/register', [RegisteredUserController::class, 'store'])
            ->middleware('guest')
            ->name('register');

        Route::post('/login', [AuthenticatedSessionController::class, 'store'])
            ->middleware('guest')
            ->name('login');

        Route::get('/google', [GoogleAuthController::class, 'redirectToGoogle']);
        Route::get('/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->middleware('guest')
            ->name('password.email');

        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->middleware('guest')
            ->name('password.store');
    });
        // Public content routes
        Route::get('/search', [SearchController::class, 'search']);
        Route::get('/search/autocomplete', [SearchController::class, 'autocomplete']);


    Route::get('/', [HomeController::class, 'index']);

    Route::get('/animes', [AnimeController::class, 'index']);
    Route::get('/animes/top100', [AnimeController::class, 'top100']);
    Route::get('/animes/{anime}', [AnimeController::class, 'show']);
    Route::get('/animes/{anime}/episodes', [AnimeController::class, 'episodes']);
    //Route::get('/animes/{anime}/episodes/{episode}', [EpisodeController::class, 'show']);
    Route::get('/animes/{anime}/characters', [AnimeController::class, 'characters']);
    Route::get('/animes/{anime}/tags', [AnimeController::class, 'tags']);
    Route::get('/animes/{anime}/ratings', [AnimeController::class, 'ratings']);
    Route::get('/animes/{anime}/comments', [AnimeController::class, 'comments']);
    Route::get('/animes/{anime}/media', [AnimeController::class, 'media']);
    Route::get('/animes/{anime}/similars', [AnimeController::class, 'similars']);    //ваще хз чи треба
    Route::get('/animes/{anime}/related', [AnimeController::class, 'related']);      //ваще хз чи треба

    Route::get('/episodes', [EpisodeController::class, 'index']);
    Route::get('/episodes/aired-after/{date}', [EpisodeController::class, 'airedAfter']);
    Route::get('/episodes/{episode}', [EpisodeController::class, 'show']);
    Route::get('/episodes/{episode}/comments', [EpisodeController::class, 'comments']);
    Route::get('/episodes/anime/{anime}', [EpisodeController::class, 'forAnime']);

    Route::get('/people', [PersonController::class, 'index']);
    Route::get('/characters', [PersonController::class, 'characters']);
    Route::get('/people/{person}', [PersonController::class, 'show']);
    Route::get('/people/{person}/animes', [PersonController::class, 'animes']);

    Route::get('/studios', [StudioController::class, 'index']);
    Route::get('/studios/{studio}', [StudioController::class, 'show']);
    Route::get('/studios/{studio}/animes', [StudioController::class, 'animes']);

    Route::get('/tags', [TagController::class, 'index']);
    Route::get('/genres', [TagController::class, 'genres']);
    Route::get('/genres/{genre}', [TagController::class, 'showGenre']);
    Route::get('/tags/{tag}', [TagController::class, 'show']);
    Route::get('/genres/{genre}/animes', [TagController::class, 'genreAnimes']);
    Route::get('/tags/{tag}/animes', [TagController::class, 'animes']);
    Route::get('/tags/{tag}/people', [TagController::class, 'people']);
    Route::get('/tags/{tag}/selections', [TagController::class, 'selections']);

    Route::get('/selections', [SelectionController::class, 'index']);
    Route::get('/selections/{selection}', [SelectionController::class, 'show']);
    Route::get('/selections/{selection}/animes', [SelectionController::class, 'animes']);
    Route::get('/selections/{selection}/persons', [SelectionController::class, 'persons']);
    Route::get('/selections/{selection}/episodes', [SelectionController::class, 'episodes']);

    // Users - public routes
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::get('/users/{user}/user-lists', [UserController::class, 'userLists']);
    Route::get('/users/{user}/ratings', [UserController::class, 'ratings']);
    Route::get('/users/{user}/comments', [UserController::class, 'comments']);
    Route::get('/users/{user}/achievements', [UserController::class, 'achievements']);

    // User Lists - public routes
    Route::get('/user-lists', [UserListController::class, 'index']);
    Route::get('/user-lists/{userList}', [UserListController::class, 'show']);
    Route::get('/user-lists/type/{type}', [UserListController::class, 'byType']);
    Route::get('/users/{user}/user-lists', [UserListController::class, 'forUser']); //TODO

    // Ratings - public routes
    Route::get('/ratings', [RatingController::class, 'index']);
    Route::get('/ratings/{rating}', [RatingController::class, 'show']);
    Route::get('/ratings/user/{user}', [RatingController::class, 'forUser']);
    Route::get('/ratings/anime/{anime}', [RatingController::class, 'forAnime']);

    // Comment likes - public routes
    Route::get('/comment-likes', [CommentLikeController::class, 'index']);
    Route::get('/comment-likes/{commentLike}', [CommentLikeController::class, 'show']);
    Route::get('/comment-likes/comment/{comment}', [CommentLikeController::class, 'forComment']);

    // Tariffs - public routes
    Route::get('/tariffs', [TariffController::class, 'index']);
    Route::get('/tariffs/{tariff}', [TariffController::class, 'show']);

    // Comments - public routes
    Route::get('/comments/recent', [CommentController::class, 'recent']);
    Route::get('/comments/roots/{commentable_type}/{commentable_id}', [CommentController::class, 'roots']);
    Route::get('/comments', [CommentController::class, 'index']);
    Route::get('/comments/{comment}', [CommentController::class, 'show']);
    Route::get('/comments/{comment}/replies', [CommentController::class, 'replies']);
});
// Protected routes (require authentication)
Route::group(['prefix' => 'v1', 'middleware' => ['auth:sanctum']], function () {
    // User verification
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/mark_read', [NotificationController::class, 'markAllRead']);
    Route::post('/notifications/{id}/mark_read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    // User profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
        return response()->json($request->user());
    });
    // Users - protected routes
    Route::get('/profile', [UserController::class, 'profile']);
//    Route::put('/users/{user}', [UserController::class, 'update']);
//    Route::patch('/users/{user}', [UserController::class, 'updatePartial']);
    Route::get('/settings', [UserController::class, 'settings']);
    Route::put('/settings', [UserController::class, 'update']);
    Route::patch('/settings', [UserController::class, 'updatePartial']);

    //Route::get('/users/{user}/subscriptions', [UserController::class, 'subscriptions']);

    Route::post('/watch-parties', [WatchPartyController::class, 'create']);
    Route::post('/watch-parties/join', [WatchPartyController::class, 'join']);
    Route::get('/watch-parties/{watchParty}/messages', [WatchPartyMessageController::class, 'index']);
    Route::post('/watch-parties/{watchParty}/messages', [WatchPartyMessageController::class, 'store']);

    // User lists - protected routes
    Route::post('/user-lists', [UserListController::class, 'store']);
    Route::delete('/user-lists/{userList}', [UserListController::class, 'destroy']);

    // Ratings - protected routes
    Route::post('/ratings', [RatingController::class, 'store']);
    Route::put('/ratings/{rating}', [RatingController::class, 'update']);
    Route::delete('/ratings/{rating}', [RatingController::class, 'destroy']);

    // Comments
    Route::post('/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::get('/comments/user/{user}', [CommentController::class, 'forUser']);

    // Comment likes - protected routes
    Route::post('/comment-likes', [CommentLikeController::class, 'store']);
    Route::get('/comment-likes/user/{user}', [CommentLikeController::class, 'forUser']);
    Route::put('/comment-likes/{commentLike}', [CommentLikeController::class, 'update']);
    Route::delete('/comment-likes/{commentLike}', [CommentLikeController::class, 'destroy']);

    // Comment reports
    Route::post('/comment-reports', [CommentReportController::class, 'store']);
    Route::get('/comment-reports/comment/{comment}', [CommentReportController::class, 'forComment']);

    // Subscriptions and payments
//    Route::get('/user-subscriptions', [UserSubscriptionController::class, 'index']);
//    Route::post('/user-subscriptions', [UserSubscriptionController::class, 'store']);
//    Route::get('/user-subscriptions/active', [UserSubscriptionController::class, 'active']);
//    Route::get('/user-subscriptions/user/{user}', [UserSubscriptionController::class, 'forUser']);
//    Route::get('/user-subscriptions/{userSubscription}', [UserSubscriptionController::class, 'show']);

//    Route::get('/payments', [PaymentController::class, 'index']);
//    Route::post('/payments', [PaymentController::class, 'store']);
//    Route::get('/payments/{payment}', [PaymentController::class, 'show']);
//    Route::get('/payments/user/{user}', [PaymentController::class, 'forUser']);

    Route::post('/episodes/{episode}/progress', [EpisodeController::class, 'updateWatchProgress']);
});

// Admin routes
Route::group(['prefix' => 'v1/admin', 'middleware' => ['auth:sanctum', 'admin']], function () {
    // User management
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::patch('/users/{user}/ban', [UserController::class, 'ban']);

    // Використовуємо спеціальний параметр для розблокування користувача
    Route::patch('/users/{id}/unban', [UserController::class, 'unban'])->whereUlid('id');

    // Content management
    Route::post('/animes', [AnimeController::class, 'store']);
    Route::put('/animes/{anime}', [AnimeController::class, 'update']);
    Route::patch('/animes/{anime}', [AnimeController::class, 'updatePartial']);
    Route::delete('/animes/{anime}', [AnimeController::class, 'destroy']);

    Route::post('/episodes', [EpisodeController::class, 'store']);
    Route::put('/episodes/{episode}', [EpisodeController::class, 'update']);
    Route::delete('/episodes/{episode}', [EpisodeController::class, 'destroy']);

    Route::post('/people', [PersonController::class, 'store']);
    Route::put('/people/{person}', [PersonController::class, 'update']);
    Route::delete('/people/{person}', [PersonController::class, 'destroy']);

    Route::post('/studios', [StudioController::class, 'store']);
    Route::put('/studios/{studio}', [StudioController::class, 'update']);
    Route::delete('/studios/{studio}', [StudioController::class, 'destroy']);

    Route::post('/tags', [TagController::class, 'store']);
    Route::put('/tags/{tag}', [TagController::class, 'update']);
    Route::delete('/tags/{tag}', [TagController::class, 'destroy']);

    Route::post('/selections', [SelectionController::class, 'store']);
    Route::put('/selections/{selection}', [SelectionController::class, 'update']);
    Route::delete('/selections/{selection}', [SelectionController::class, 'destroy']);

    // Moderation
    Route::get('/comment-reports', [CommentReportController::class, 'index']);
    Route::get('/comment-reports/unviewed', [CommentReportController::class, 'unviewed']);
    Route::get('/comment-reports/{commentReport}', [CommentReportController::class, 'show']);
    Route::put('/comment-reports/{commentReport}', [CommentReportController::class, 'update']);
    Route::delete('/comment-reports/{commentReport}', [CommentReportController::class, 'destroy']);

    // Subscription management
    Route::post('/tariffs', [TariffController::class, 'store']);
    Route::put('/tariffs/{tariff}', [TariffController::class, 'update']);
    Route::delete('/tariffs/{tariff}', [TariffController::class, 'destroy']);


});

