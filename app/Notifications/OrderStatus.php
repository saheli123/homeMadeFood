<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatus extends Notification implements ShouldQueue
{
    use Queueable;
    private $sender;
    private $order;
    private $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sender,$order)
    {
        $this->sender = $sender;
        $this->order = $order;
        $status=$order->status;
        $msg="";
        switch($status){
            case 1:$msg="approved";break;
            case 2:$msg="cancelled";break;
            case 3:$msg="delivered";break;

        }
        $this->message=$this->sender->name . ", ".$msg. " your order.";

    }
    public function toBroadcast($notifiable)
    {
        // $timestamp = Carbon::now()->addSecond()->toDateTimeString();
        return [
            "data"=>
            ['sender_id' => $this->sender->id,
            'order_id' => $this->order->id,
            'url'=>'/order',
            'message'=>$this->message
            ]
        ];
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','mail', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->greeting("Hello ".$notifiable->name." , ")
        ->subject('Status changed for order  #' . $this->order->id)
        ->line($this->message)
        ->action('View Order', "/");
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {

        return [
            'sender_id' => $this->sender->id,
            'order_id' => $this->order->id,
            'url'=>'/order',
            'image'=>$this->sender->profile && $this->sender->profile->image?url($this->sender->profile->image):url('img/food_default.jpg'),
            'message'=>$this->message
        ];
    }
}
