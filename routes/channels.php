<?php

use App\Features\Comments\Models\Comment;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('post.{postId}', function ($user, $postId) {
    return Comment::where('post_id', $postId)
        ->where('user_id', $user->id)
        ->exists();
});
