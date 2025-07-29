<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use AnimeSite\Enums\CommentReportType;
use AnimeSite\Models\Comment;
use AnimeSite\Models\CommentReport;
use AnimeSite\Models\User;

/**
 * @extends Factory<CommentReport>
 */
class CommentReportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'comment_id' => Comment::factory(),
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(CommentReportType::cases()),
            'is_viewed' => $this->faker->boolean(50),
            'body' => $this->faker->optional()->paragraph(),
        ];
    }

    public function forCommentAndUser(Comment $comment, User $user): self
    {
        return $this->state([
            'comment_id' => $comment->id,
            'user_id' => $user->id,
        ]);
    }

    public function withType(CommentReportType $type): self
    {
        return $this->state([
            'type' => $type->value,
        ]);
    }
}
