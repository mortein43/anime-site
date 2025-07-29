<?php

namespace AnimeSite\Actions\Tags;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use AnimeSite\Models\Tag;

class GetTagAnimes
{
    /**
     * Отримати список аніме пов'язаних з тегом з пагінацією.
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

        return $tag->animes()
            ->when($request->filled('search'), fn($q) =>
                $q->where('name', 'ILIKE', '%' . $request->input('search') . '%')
                  ->orWhereJsonContains('aliases', $request->input('search'))
            )
            ->when($request->filled('status'), fn($q) =>
                $q->where('status', $request->input('status'))
            )
            ->when($request->filled('kind'), fn($q) =>
                $q->where('kind', $request->input('kind'))
            )
            ->when($request->filled('year'), fn($q) =>
                $q->whereYear('first_air_date', $request->input('year'))
            )
            ->where('is_published', true) // Показуємо тільки опубліковані аніме
            ->with(['studio', 'tags'])
            ->orderBy('name')
            ->paginate($perPage);
    }
}
