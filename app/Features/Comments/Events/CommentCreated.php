<?php

namespace App\Features\Comments\Events;

use App\Features\Comments\Models\Comment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentCreated implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('post.'.$this->comment->post_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->comment->id,
            'text' => $this->comment->text,
            'user' => [
                'id' => $this->comment->user->id,
                'name' => $this->comment->user->name
            ],
            'created_at' => $this->comment->created_at->toDateTimeString()
        ];
    }

    public function broadcastAs()
    {
        return 'comment.created';
    }
}
