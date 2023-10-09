<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileProcessingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $status;
    public $percentage;

    public function __construct($status, $percentage)
    {
        $this->status = $status;
        $this->percentage = $percentage;
    }

    public function broadcastOn()
    {
        // Define the broadcasting channel (e.g., public or private channel)
        return ['file-processing'];
    }
}