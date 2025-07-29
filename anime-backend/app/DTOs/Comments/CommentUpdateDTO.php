<?php

namespace AnimeSite\DTOs\Comments;

use AnimeSite\DTOs\BaseDTO;
use Illuminate\Http\Request;

class CommentUpdateDTO extends BaseDTO
{
    /**
     * Create a new CommentUpdateDTO instance.
     *
     * @param string $body Comment body
     * @param bool|null $isSpoiler Whether the comment contains spoilers
     * @param bool|null $isApproved Whether the comment approved
     */
    public function __construct(
        public readonly string $body,
        public readonly ?bool $isSpoiler = null,
        public readonly ?bool $isApproved = null,
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
            body: $request->input('body'),
            isSpoiler: $request->has('is_spoiler') ? $request->boolean('is_spoiler') : null,
            isApproved: $request->has('is_approved') ? $request->boolean('is_approved') : null,
        );
    }
}
