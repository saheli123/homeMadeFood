<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class Checkout extends Notification implements ShouldQueue
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
    public function __construct($sender, $order)
    {
        //
        $this->sender = $sender;
        $this->order = $order;
        $this->message=$this->sender->name . " order your dish.Do you want to accept his order?";

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
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Order placed #' . $this->order->id)
            ->action('Notification Action', url('/'))
            ->line($this->message);
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
