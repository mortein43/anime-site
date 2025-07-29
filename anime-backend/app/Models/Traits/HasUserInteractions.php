<?php

namespace AnimeSite\Models\Traits;

use AnimeSite\Models\Comment;
use AnimeSite\Models\UserList;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait for models that users can interact with (comments, lists, etc.)
 */
trait HasUserInteractions
{
    /**
     * Get all user lists associated with this model.
     */
    public function userLists(): MorphMany
    {
        return $this->morphMany(UserList::class, 'listable');
    }

    /**
     * Get all comments associated with this model.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
