<?php

namespace AnimeSite\Actions\Comments;

use AnimeSite\DTOs\Comments\CommentUpdateDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use AnimeSite\Models\Comment;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateComment
{
    use AsAction;

    /**
     * Update an existing comment.
     *
     * @param Comment $comment
     * @param  CommentUpdateDTO  $dto
     * @return Comment
     */
    public function handle(Comment $comment, CommentUpdateDTO $dto): Comment
    {
        $comment->body = $dto->body;

        if ($dto->isSpoiler !== null) {
            $comment->is_spoiler = $dto->isSpoiler;
        }

        if ($dto->isApproved !== null) {
            $comment->is_approved = $dto->isApproved;
        }

        $comment->save();

        return $comment->load(['user'])->loadCount(['likes', 'children']);
    }
}
