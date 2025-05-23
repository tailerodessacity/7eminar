<?php

namespace App\Features\Comments\Services;

use App\Features\Comments\Jobs\SendCommentNotification;
use App\Features\Comments\Notifications\NewCommentNotification;
use App\Features\Posts\Models\Post;
use App\Features\Posts\Repositories\PostRepository;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class CommentNotifyService
{
    public function __construct(private PostRepository $commentRepository)
    {
    }

    public function notify(Post $post, string $currentUserEmail)
    {
        $jobs = [];

        foreach ($this->commentRepository->filterUniqueEmail($post, $currentUserEmail) as $comment) {
            $jobs[] = new SendCommentNotification($comment, new NewCommentNotification());
        }

        $batch = Bus::batch($jobs)
            ->then(function (Batch $batch) {
            })->finally(function (Batch $batch) {
                $countSuccessfullyJobsCompleted = $batch->totalJobs - $batch->failedJobs;
                Log::info(sprintf(
                    'Comments notifier: total failed jobs %s and successfully completed %s',
                    $batch->failedJobs,
                    $countSuccessfullyJobsCompleted
                ));
            })->name('New Comment')
            ->onQueue('notification_comments')
            ->dispatch();

        return $batch;
    }
}
