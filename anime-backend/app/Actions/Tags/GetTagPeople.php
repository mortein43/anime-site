<?php

namespace AnimeSite\Actions\Tags;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use AnimeSite\Models\Tag;

class GetTagPeople
{
    /**
     * Отримати список людей пов'язаних з тегом з пагінацією.
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

        return $tag->people()
            ->when($request->filled('search'), fn($q) =>
                $q->where('name', 'ILIKE', '%' . $request->input('search') . '%')
                  ->orWhere('original_name', 'ILIKE', '%' . $request->input('search') . '%')
            )
            ->when($request->filled('type'), fn($q) =>
                $q->where('type', $request->input('type'))
            )
            ->when($request->filled('gender'), fn($q) =>
                $q->where('gender', $request->input('gender'))
            )
            ->orderBy('name')
            ->paginate($perPage);
    }
}
