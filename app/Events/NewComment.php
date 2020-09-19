<?php

namespace App\Events;

use App\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewComment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('comments.' . $this->comment->post_id);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $profile_photo = "";

        if($this->comment->author->photo){
            $profile_photo = $this->comment->author->photo->getUrl('thumb');
        } else {
            $profile_photo = asset('/images/default.png');
        }

        return [
            'comment' => [
                'id' => $this->comment->id,
                'author' => $this->comment->author->name,
                'profile_photo' => $profile_photo,
                'created_at' => $this->comment->created_at->format('F j, Y \a\t H:ia'),
                'body' => $this->comment->body
            ]
        ];
    }
}
