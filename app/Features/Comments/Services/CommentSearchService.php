<?php

namespace App\Features\Comments\Services;

use App\Features\Comments\Repositories\CommentRepository;
use Illuminate\Pagination\CursorPaginator;

class CommentSearchService
{
    public function __construct(
        protected CommentRepository $repository
    ) {}

    public function search(array $filters): CursorPaginator
    {
        return $this->repository->search($filters) ->cursorPaginate(15);
    }

}

