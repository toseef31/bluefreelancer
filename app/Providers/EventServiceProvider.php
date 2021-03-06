<?php

namespace App\Providers;

use App\Models\Bid;
use App\Models\Feedback;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Transaction;
use App\Observers\BidObserver;
use App\Observers\FeedbackObserver;
use App\Observers\MilestoneObserver;
use App\Observers\ProjectObserver;
use App\Observers\TransactionObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Bid::observe(BidObserver::class);
        Project::observe(ProjectObserver::class);
        Transaction::observe(TransactionObserver::class);
        Milestone::observe(MilestoneObserver::class);
        Feedback::observe(FeedbackObserver::class);
    }
}
