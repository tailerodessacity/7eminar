<?php

namespace App\Features\Comments\Actions;

use App\Features\Comments\DTOs\EditCommentDto;
use App\Features\Comments\Models\Comment;

class EditCommentAction
{
    public function execute(EditCommentDto $dto, Comment $comment): Comment
    {
        $comment->updateOrFail(['text' => $dto->text,]);
        return $comment;
    }
}
