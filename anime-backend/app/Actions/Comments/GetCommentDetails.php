<?php

namespace AnimeSite\Actions\Comments;

use AnimeSite\Models\Comment;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCommentDetails
{
    use AsAction;

    /**
     * Get detailed information about a specific comment.
     *
     * @param  Comment  $comment
     * @return Comment
     */
    public function handle(Comment $comment): Comment
    {
        return $comment->load(['user', 'parent', 'commentable'])->loadCount(['likes', 'children']);
    }
}
