<?php

namespace AnimeSite\Actions\Comments;

use AnimeSite\DTOs\Comments\CommentStoreDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use AnimeSite\Models\Comment;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateComment
{
    use AsAction;

    /**
     * Create a new comment.
     *
     * @param  CommentStoreDTO  $dto
     * @return Comment
     */
    public function handle(CommentStoreDTO $dto): Comment
    {
        $comment = new Comment();
        $comment->user_id = $dto->userId;
        $comment->commentable_type = $dto->commentableType;
        $comment->commentable_id = $dto->commentableId;
        $comment->body = $dto->body;
        $comment->is_spoiler = $dto->isSpoiler;
        $comment->is_approved = $dto->isApproved;
        $comment->save();

        return $comment->load(['user'])->loadCount(['likes', 'children']);
    }
}
