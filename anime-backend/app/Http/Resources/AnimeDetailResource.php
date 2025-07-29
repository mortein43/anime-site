<?php

namespace AnimeSite\Http\Resources;
use AnimeSite\Enums\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnimeDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => strip_tags($this->description),
            //'image_name' => $this->image_name,
            'poster' => $this->when(!is_null($this->poster), $this->poster),
            'duration' => $this->when(!is_null($this->duration), $this->duration),
            'episodes_count' => $this->when(!is_null($this->episodes_count), $this->episodes_count),
            'first_air_date' => $this->first_air_date,
            'last_air_date' => $this->last_air_date,
            'imdb_score' => $this->imdb_score,
            'is_published' => $this->is_published,
            'kind' => $this->kind->name() ?? null,

            'aliases' => $this->aliases ?? [],
            'countries' => $this->countries ?? [],
            'attachments' => $this->attachments ?? [],
            'related' => $this->related ?? [],
            'relation_type' => $this->relation_type ?? null,
            'similars' => $this->similars ?? [],
            'api_sources' => $this->api_sources ?? [],

            'studio' => $this->whenLoaded('studio', function () {
                return [
                    'id' => $this->studio->id,
                    'name' => $this->studio->name,
                    'slug' => $this->studio->slug,
                    'description' => $this->studio->description,
                    'image' => $this->studio->image,
                ];
            }),

            'people' => $this->whenLoaded('people', function () {
                $characters = $this->people
                    ->where('type', 'character')
                    ->take(3)
                    ->map(function ($person) {
                        return [
                            'slug' => $person->slug,
                            'name' => $person->name,
                            'image' => $person->image,
                            'birthday' => $person->birthday,
                            'age' => $person->birthday ? now()->diffInYears($person->birthday) : null,
                            'type' => $person->type,
                        ];
                    });

                $authors = $this->people
                    ->where('type', '!=', 'character')
                    ->take(3)
                    ->map(function ($person) {
                        return [
                            'slug' => $person->slug,
                            'name' => $person->name,
                            'image' => $person->image,
                            'birthday' => $person->birthday,
                            'age' => $person->birthday ? now()->diffInYears($person->birthday) : null,
                            'type' => $person->type,
                        ];
                    });

                return [
                    'characters' => $characters->values(),
                    'authors' => $authors->values(),
                ];
            }),

            'tags' => $this->whenLoaded('tags', function () {
                return TagResource::collection($this->tags);
            }),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'seo' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
                'image' => $this->meta_image,
            ],

            'comments' => $this->whenLoaded('comments', function () {
                return $this->comments->map(function ($comment) {
                    return $this->transformComment($comment);
                });
            }),

            'ratings' => $this->whenLoaded('ratings', function () {
                return $this->ratings->take(2)->map(function ($ratings) {
                    return [
                        'id' => $ratings->id,
                        'user' => [
                            'id' => $ratings->user->id,
                            'name' => $ratings->user->name,
                            'avatar' => $ratings->user->avatar,
                        ],
                        'review' => $ratings->review,
                        'number' => $ratings->number,
                        'created_at' => $ratings->created_at->diffForHumans(),
                    ];
                });
            }),

            'episodes' => $this->whenLoaded('episodes', function () {
                return $this->episodes->map(function ($episode) {
                    return [
                        'id' => $episode->id,
                        'slug' => $episode->slug,
                        'name' => $episode->name,
                        'number' => $episode->number,
                        'pictures' => $episode->pictures[0] ?? null,
                        'air_date' => $episode->air_date,
                        'duration' => $episode->duration,
                    ];
                });
            }),
        ];
    }
    protected function transformComment($comment)
    {
        ///dd($comment->likes->pluck('is_like'));
         $comment->loadMissing('likes');
        return [
            'id' => $comment->id,
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
                'avatar' => $comment->user->avatar,
            ],
            'body' => $comment->body,
            'created_at' => $comment->created_at->diffForHumans(),
            'children' => $comment->children->map(function ($child) {
                return $this->transformComment($child);
            }),
            'count_likes' => $comment->likes->where('is_liked', true)->count(),
            'count_dislikes' => $comment->likes->where('is_liked', false)->count(),
        ];
    }
}
