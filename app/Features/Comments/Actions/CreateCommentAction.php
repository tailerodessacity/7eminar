<?php

namespace App\Features\Comments\Actions;

use App\Features\Comments\DTOs\CreateCommentDto;
use App\Features\Comments\Models\Comment;

class CreateCommentAction
{
    public function execute(CreateCommentDto $dto): Comment
    {
        return Comment::create([
            'text' => $dto->text,
            'user_id' => $dto->userId,
            'post_id' => $dto->postId,
        ]);
    }
}
