<?php

namespace Database\Seeders;

use App\Features\Comments\Models\Comment;
use App\Features\Posts\Models\Post;
use App\Features\User\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(5)->create();

        $users->each(function ($user) {
            $user->createToken('default-token', [
                'comments:create',
                'comments:update',
                'comments:delete'
            ]);
        });

        $users->each(function ($user) {
            Post::factory(3)->create();
        });

        $posts = Post::all();

        foreach ($posts as $post) {
            Comment::factory(rand(2, 5))->create([
                'post_id' => $post->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
