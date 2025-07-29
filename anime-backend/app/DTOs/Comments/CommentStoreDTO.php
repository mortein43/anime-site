<?php

namespace AnimeSite\DTOs\Comments;

use AnimeSite\DTOs\BaseDTO;
use Illuminate\Http\Request;

class CommentStoreDTO extends BaseDTO
{
    /**
     * Create a new CommentStoreDTO instance.
     *
     * @param string $userId User ID
     * @param string $commentableType Commentable type
     * @param string $commentableId Commentable ID
     * @param string $body Comment body
     * @param bool $isSpoiler Whether the comment contains spoilers
     * @param bool $isApproved Whether the comment approved
     */
    public function __construct(
        public readonly string $userId,
        public readonly string $commentableType,
        public readonly string $commentableId,
        public readonly string $body,
        public readonly bool $isSpoiler = false,
        public readonly bool $isApproved = true,
    ) {
    }

    /**
     * Get the fields that should be used for the DTO.
     *
     * @return array
     */
    public static function fields(): array
    {
        return [
            'user_id' => 'userId',
            'commentable_type' => 'commentableType',
            'commentable_id' => 'commentableId',
            'body',
            'is_spoiler' => 'isSpoiler',
            'is_approved' => 'isApproved',
        ];
    }

    /**
     * Create a new DTO instance from request.
     *
     * @param Request $request
     * @return static
     */
    public static function fromRequest(Request $request): static
    {
        return new static(
            userId: $request->user()->id,
            commentableType: $request->input('commentable_type'),
            commentableId: $request->input('commentable_id'),
            body: $request->input('body'),
            isSpoiler: $request->boolean('is_spoiler', false),
            isApproved: $request->boolean('is_approved', true),
        );
    }
}
