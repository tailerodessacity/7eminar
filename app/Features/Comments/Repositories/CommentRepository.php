<?php

namespace App\Features\Comments\Repositories;

use App\Features\Comments\Models\Comment;
use App\Features\Comments\Filters\SearchCommentFilter;
use Illuminate\Contracts\Pagination\CursorPaginator;

class CommentRepository
{
    public function search(array $filters): CursorPaginator
    {
        $filter = new SearchCommentFilter($filters);

        $limit = min(max((int) ($filters['limit'] ?? 15), 1), 50);

        return $filter->apply(Comment::query())
            ->with('user')
            ->orderByDesc('created_at')
            ->orderBy('id')
            ->cursorPaginate($limit);
    }
}
