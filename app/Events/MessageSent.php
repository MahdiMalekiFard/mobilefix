<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    /** Create a new event instance. */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
        ];
        
        if ($this->message->sender_type === 'user') {
            // User messages: broadcast to global admin channel
            $channels[] = new PrivateChannel('admin-chat');
        } else {
            // Admin messages: broadcast to specific user channel for live badge updates
            $channels[] = new PrivateChannel('user-chat.' . $this->message->conversation->user_id);
        }
        
        return $channels;
    }

    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id'              => $this->message->id,
                'conversation_id' => $this->message->conversation_id,
                'sender_id'       => $this->message->sender_id,
                'sender_type'     => $this->message->sender_type,
                'body'            => $this->message->body,
                'created_at'      => $this->message->created_at,
                'media'           => $this->message->getMedia('attachments')->map(function ($media) {
                    return [
                        'id'        => $media->id,
                        'file_name' => $media->file_name,
                        'mime_type' => $media->mime_type,
                        'size'      => $media->size,
                        'url'       => $media->getFullUrl(),
                    ];
                }),
            ],
        ];
    }
}
