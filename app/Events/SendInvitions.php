<?php

namespace App\Events;

use App\Models\Invition;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendInvitions implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $reviever_id;

    public function __construct($message,$reviever_id)
    {
        $this->message = $message;
        $this->reviever_id =$reviever_id;
    }

    public function broadcastOn()
    {
        return ['my-channel'.$this->reviever_id];
    }

    public function broadcastAs()
    {
        return 'my-event';
    }

//    public function broadcastWith(){
//        return [
//            'message'=>$this->message,
//            'reviever_id'=>$this->reviever_id
//        ];
//    }

}
