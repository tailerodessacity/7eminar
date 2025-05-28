<?php

namespace App\Features\Comments\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

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
            $method = Str::camel($key);

            if (method_exists($this, $method) && filled($value)) {
                $this->{$method}($value);
            }
        }

        return $this->builder;
    }

    protected function text(string $value): void
    {
        $this->builder->whereFullText('text', $value, [
            'mode' => 'boolean'
        ]);
    }

    protected function author(string $value): void
    {
        $this->builder->whereHas('user', function (Builder $query) use ($value) {
            $query->whereFullText('name', $value, ['mode' => 'boolean']);
        });
    }

    protected function postId(int $value): void
    {
        $this->builder->where('post_id', $value);
    }

    protected function dateFrom(string $value): void
    {
        $this->builder->where('created_at', '>=', Carbon::parse($value)->startOfDay());
    }

    protected function dateTo(string $value): void
    {
        $this->builder->where('created_at', '<=', Carbon::parse($value)->endOfDay());
    }

    protected function userId(int $value): void
    {
        $this->builder->where('user_id', $value);
    }
}
