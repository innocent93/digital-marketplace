<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     */

    protected $listen = [
        \App\Events\PurchaseMade::class => [
            \App\Listeners\SendPurchaseNotification::class,
        ],
    ];
    public function boot(): void
    {
        //
    }
}       