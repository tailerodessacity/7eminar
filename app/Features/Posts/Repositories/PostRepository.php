<?php

namespace App\Features\Posts\Repositories;

use App\Features\Comments\Models\Comment;
use App\Features\Posts\Models\Post;
use Illuminate\Contracts\Pagination\CursorPaginator;

class PostRepository
{
    public function getByPostId(int $postId): CursorPaginator
    {
        return Comment::with('user')
            ->where('post_id', $postId)
            ->orderBy('id')
            ->cursorPaginate(20);
    }

    public function find(int $id): ?Comment
    {
        return Comment::with('user')->find($id);
    }

    public function filterUniqueEmail(Post $post, string $currentUserEmail)
    {
        return $post->comments->unique('email')
            ->filter(function (string $email) use ($currentUserEmail) {
                return $email != $currentUserEmail;
            });
    }
}

