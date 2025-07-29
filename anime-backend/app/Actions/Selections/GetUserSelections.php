<?php

namespace AnimeSite\Actions\Selections;

use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use AnimeSite\Models\User;
use AnimeSite\Models\Selection;

class GetUserSelections
{
    /**
     * Отримати добірки конкретного користувача з пагінацією та фільтрацією.
     *
     * @param User $user
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function __invoke(User $user, Request $request): LengthAwarePaginator
    {
        // Перевіряємо права доступу до перегляду добірок користувача
        Gate::authorize('viewUserSelections', [Selection::class, $user]);

        $perPage = (int) $request->input('per_page', 15);
        
        $currentUser = auth()->user();
        $isOwnerOrAdmin = $currentUser && (
            $currentUser->id === $user->id || 
            $currentUser->isAdmin() || 
            $currentUser->isModerator()
        );

        return Selection::query()
            // Фільтрація за користувачем
            ->where('user_id', $user->id)
            // Якщо користувач не власник і не адмін, показуємо тільки опубліковані добірки
            ->when(!$isOwnerOrAdmin, function($query) {
                return $query->where('is_published', true);
            })
            // Повнотекстовий пошук
            ->when($request->filled('search'), function($query) use ($request) {
                return $query->where(function($q) use ($request) {
                    $search = $request->input('search');
                    $q->where('name', 'ILIKE', "%{$search}%")
                      ->orWhere('description', 'ILIKE', "%{$search}%");
                });
            })
            // Фільтрація за статусом публікації (тільки для власника/адміна)
            ->when($request->filled('is_published') && $isOwnerOrAdmin, function($query) use ($request) {
                return $query->where('is_published', filter_var($request->input('is_published'), FILTER_VALIDATE_BOOLEAN));
            })
            // Сортування
            ->when($request->filled('sort_by'), function($query) use ($request) {
                $sortField = $request->input('sort_by', 'created_at');
                $sortDirection = $request->input('sort_direction', 'desc');
                
                $allowedSortFields = ['name', 'created_at', 'updated_at'];
                if (in_array($sortField, $allowedSortFields)) {
                    return $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
                }
                return $query;
            }, function($query) {
                return $query->orderBy('created_at', 'desc');
            })
            // Завантаження зв'язків
            ->with(['user', 'animes', 'persons', 'episodes', 'tags'])
            // Пагінація
            ->paginate($perPage);
    }
}
