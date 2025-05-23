<?php

namespace Database\Factories;

use App\Features\Comments\Models\Comment;
use App\Features\Posts\Models\Post;
use App\Features\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'text' => $this->faker->paragraph(),
            'user_id' => User::factory(),
            'post_id' => Post::factory(),
        ];
    }
}
