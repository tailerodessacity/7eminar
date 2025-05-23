<?php

namespace App\Features\Comments\Jobs;

use App\Features\Comments\Models\Comment;
use App\Features\Comments\Notifications\NewCommentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCommentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly Comment                $comment,
        private readonly NewCommentNotification $notification)
    {
    }

    public function handle()
    {
        $this->comment->notify($this->notification);
    }
}
