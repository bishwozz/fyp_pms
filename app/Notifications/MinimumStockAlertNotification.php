<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MinimumStockAlertNotification extends Notification
{
    use Queueable;

    protected $stock;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($stock)
    {
        $this->stock = $stock;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast','mail'];
    }

    public function toMail($notifiable)
    {
        $message = $this->stock->itemEntity->name.' is below minimum stock amount';
        return (new MailMessage)
        ->subject('Stock Minimum quantity reached.')
        ->greeting('Hello!')
        ->line($message)
        ->action('Check it out', url('/stock-entry'))
        ->line('Best regards!');

    }

        /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'item' => $this->stock->itemEntity,
            'message' => $this->stock->itemEntity->name.' is below minimum stock amount'

        ]);
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
            'item' => $this->stock->itemEntity,
            'message' => $this->stock->itemEntity->name.' is below minimum stock amount'
        ];
    }

        /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'item' => $this->stock->itemEntity,
            'message' => $this->stock->itemEntity->name.' is below minimum stock amount'
        ];
    }

        /**
     * Get the type of the notification being broadcast.
     *
     * @return string
     */
    public function broadcastType()
    {
        return 'stockminimum.alert';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('stockMinimumAlert');
    }
}
