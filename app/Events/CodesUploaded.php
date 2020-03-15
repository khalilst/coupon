<?php

namespace App\Events;

use App\Models\Coupon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CodesUploaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The Coupon instance
     *
     * @var Coupon
     */
    public $coupon;

    /**
     * Create a new event instance.
     *
     * @param  Coupon $coupon
     * @return void
     */
    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }
}
