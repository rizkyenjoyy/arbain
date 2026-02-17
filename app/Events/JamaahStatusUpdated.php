<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JamaahStatusUpdated
{
    use Dispatchable, SerializesModels;

    public $jamaah;

    public function __construct(Jamaah $jamaah)
    {
        $this->jamaah = $jamaah;
    }
}
