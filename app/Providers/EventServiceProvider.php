<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        \App\Events\OrderCreatedEvent::class => [
            \App\Listeners\SendOrderCreatedNotification::class,
        ],
        \App\Events\OrderApprovedEvent::class => [
            \App\Listeners\SendOrderApprovedNotification::class,
        ],
        \App\Events\OrderRejectedEvent::class => [
            \App\Listeners\SendOrderRejectedNotification::class,
        ],
        \App\Events\OrderCompletedEvent::class => [
            \App\Listeners\SendOrderCompletedNotification::class,
        ],
        \App\Events\PaymentReceivedEvent::class => [
            \App\Listeners\SendPaymentReceivedNotification::class,
        ],
        \App\Events\CustomDesignUploadedEvent::class => [
            \App\Listeners\SendCustomDesignUploadedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
