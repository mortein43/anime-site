<?php

namespace AnimeSite\Actions\Tags;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use AnimeSite\Models\Tag;

class GetTagSelections
{
    /**
     * Отримати список добірок пов'язаних з тегом з пагінацією.
     *
     * @param Tag $tag
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function __invoke(Tag $tag, Request $request): LengthAwarePaginator
    {
        // Дозволяємо перегляд без авторизації
        // Gate::authorize('view', $tag);

        $perPage = (int) $request->input('per_page', 15);

        return $tag->selections()
            ->when($request->filled('search'), fn($q) =>
                $q->where('name', 'ILIKE', '%' . $request->input('search') . '%')
                  ->orWhere('description', 'ILIKE', '%' . $request->input('search') . '%')
            )
            ->when($request->filled('is_published'), fn($q) =>
                $q->where('is_published', filter_var($request->input('is_published'), FILTER_VALIDATE_BOOLEAN))
            )
            ->where('is_published', true) // Показуємо тільки опубліковані добірки
            ->orderBy('name')
            ->paginate($perPage);
    }
}
