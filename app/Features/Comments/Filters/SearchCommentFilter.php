<?php

namespace App\Features\Comments\Filters;

use Illuminate\Database\Eloquent\Builder;

class SearchCommentFilter
{
    protected array $filters;
    protected Builder $builder;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->filters as $key => $value) {
            if (method_exists($this, $key) && filled($value)) {
                $this->{$key}($value);
            }
        }

        return $this->builder;
    }

    protected function text(string $value): void
    {
        $this->builder->where('text', 'like', '%' . $value . '%');
    }

    protected function author(string $value): void
    {
        $this->builder->whereHas('user', function (Builder $query) use ($value) {
            $query->where('name', 'like', '%' . $value . '%');
        });
    }

    protected function post_id(int $value): void
    {
        $this->builder->where('post_id', $value);
    }
}
