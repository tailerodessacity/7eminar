<?php

namespace App\Features\Comments\Actions;

use App\Features\Comments\Models\Comment;

class DeleteCommentAction
{
    public function execute(Comment $comment): bool
    {
        return $comment->deleteOrFail();
    }
}
