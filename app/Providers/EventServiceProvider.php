<?php

namespace App\Providers;

use App\Features\Comments\Events\CommentCreated;
use App\Features\Comments\Listeners\SendEmailCommentCreatedListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(
            CommentCreated::class,
            SendEmailCommentCreatedListener::class
        );
    }
}
