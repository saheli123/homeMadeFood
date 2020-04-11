<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheckOutEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $user;
    public $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user,$order)
    {
        //
        $this->user=$user;
        $this->message=$this->user->name." placed an order(#".$order->id.") to your dish";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['phorons-channel3'];
    }

    public function broadcastAs()
    {
        return 'mynotification';
    }
    public function broadcastWith(){
        return [
            "msg"=>$this->message
        ];
    }
}
