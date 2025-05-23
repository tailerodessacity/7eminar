<?php

namespace App\Features\Posts\Resources;

use App\Features\Comments\Resources\CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\CursorPaginator;

class PostResource extends JsonResource
{
    public function __construct($post, private readonly CursorPaginator $comments)
    {
        parent::__construct($post);
    }

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->text,
            'comments' => CommentResource::collection($this->comments->items()),
            'pagination' => [
                'next_cursor' => $this->comments->nextCursor()?->encode(),
                'prev_cursor' => $this->comments->previousCursor()?->encode(),
            ],
        ];
    }
}
