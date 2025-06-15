<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $areaId;

    public function __construct($areaId)
    {
        $this->areaId = $areaId;
    }

    public function broadcastOn()
    {
        return new Channel('documents');
    }

    public function broadcastAs()
    {
        return 'document-created';
    }
}