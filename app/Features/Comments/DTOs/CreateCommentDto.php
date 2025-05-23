<?php

namespace App\Features\Comments\DTOs;

class CreateCommentDto
{
    public function __construct(
        public readonly string $text,
        public readonly int $userId,
        public readonly int $postId,
    ) {}

    public static function fromRequest(array $data, int $postId, int $userId): self
    {
        return new self(
            text: $data['text'],
            userId: $userId,
            postId: $postId,
        );
    }
}
