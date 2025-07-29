<?php

namespace AnimeSite\Http\Resources;

use AnimeSite\Http\Resources\EpisodeResource;
use AnimeSite\Http\Resources\AnimeResource;
use AnimeSite\Http\Resources\SelectionResource;
use AnimeSite\Http\Resources\UserResource;
use AnimeSite\Models\Comment;
use AnimeSite\Models\Episode;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Selection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Comment
 */
class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'is_spoiler' => $this->is_spoiler,
            'is_approved' => $this->is_approved,
            'is_reply' => $this->is_reply,
            'user_id' => $this->user_id,
            'commentable_type' => $this->commentable_type,
            'commentable_id' => $this->commentable_id,
            'commentable_type_label' => $this->getTranslatedTypeAttribute(),
            'likes_count' => $this->when(isset($this->likes_count), fn() => $this->likes_count ?? 0),
            'dislikes_count' => $this->when(isset($this->dislikes_count), fn() => $this->dislikes_count ?? 0),
            'replies_count' => $this->when(isset($this->children_count), fn() => $this->children_count ?? 0),
            'user' => new UserResource($this->whenLoaded('user')),
            'parent' => new CommentResource($this->whenLoaded('parent')),
            'commentable' => $this->when($this->relationLoaded('commentable'), function () {
                return match ($this->commentable_type) {
                    Anime::class => new AnimeResource($this->commentable),
                    Episode::class => new EpisodeResource($this->commentable),
                    Selection::class => new SelectionResource($this->commentable),
                    default => null,
                };
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
