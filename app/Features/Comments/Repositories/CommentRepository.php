<?php

namespace App\Features\Comments\Repositories;

use App\Features\Comments\Models\Comment;
use Illuminate\Database\Eloquent\Builder;

class CommentRepository
{
    public function search(array $filters): Builder
    {
        return Comment::query()
            ->when(!empty($filters['text']), function (Builder $query) use ($filters) {
                $query->where('text', 'like', '%' . $filters['text'] . '%');
            })
            ->when(!empty($filters['author']), function (Builder $query) use ($filters) {
                $query->whereHas('user', function (Builder $q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['author'] . '%');
                });
            })
            ->when(!empty($filters['post_id']), function (Builder $query) use ($filters) {
                $query->where('post_id', $filters['post_id']);
            })
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->orderBy('id');
    }
}
