<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentUpload
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $localFilePath;
    public $s3FilePath;
    public $documentName;
    public $size;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId, $localFilePath, $s3FilePath, $documentName, $size)
    {
        $this->userId = $userId;
        $this->localFilePath = $localFilePath;
        $this->s3FilePath = $s3FilePath;
        $this->documentName = $documentName;
        $this->size = $size;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
