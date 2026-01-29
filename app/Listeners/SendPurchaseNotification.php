<?php

namespace App\Listeners;

use App\Events\PurchaseMade;
use App\Jobs\SendPurchaseNotificationJob;

class SendPurchaseNotification
{
    public function handle(PurchaseMade $event)
    {
        SendPurchaseNotificationJob::dispatch($event->purchase);
    }
}