<?php

namespace App\Features\Comments\DTOs;

class EditCommentDto
{
    public function __construct(
        public readonly string $text
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self($data['text']);
    }
}
