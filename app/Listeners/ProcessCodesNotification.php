<?php

namespace App\Listeners;

use App\Events\CodesUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessCodesNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  CodesUploaded  $event
     * @return void
     */
    public function handle(CodesUploaded $event)
    {
        $event->coupon->processCodes();
    }
}
