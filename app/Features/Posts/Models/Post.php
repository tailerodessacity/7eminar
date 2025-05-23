<?php

namespace App\Features\Posts\Models;

use App\Features\Comments\Models\Comment;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\CursorPaginator;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getPostComments(): CursorPaginator
    {
        return $this->comments()
            ->orderBy('id')
            ->cursorPaginate(20);
    }

    protected static function newFactory()
    {
        return PostFactory::new();
    }

}

