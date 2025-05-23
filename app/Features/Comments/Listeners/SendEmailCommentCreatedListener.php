<?php

namespace App\Features\Comments\Listeners;

use App\Features\Comments\Events\CommentCreated;
use App\Features\Comments\Jobs\SendCommentNotification;
use App\Features\Comments\Notifications\NewCommentNotification;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendEmailCommentCreatedListener
{
    public function handle(CommentCreated $event): void
    {
        try {
            $comment = $event->comment;
            SendCommentNotification::dispatch($comment, new NewCommentNotification());
        } catch (Throwable $e) {
            Log::error('Error processing CommentCreated event', [
                'comment_id' => $event->comment->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
