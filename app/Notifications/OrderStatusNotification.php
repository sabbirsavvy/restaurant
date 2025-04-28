<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class OrderStatusNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $status = ucfirst($this->order->status);

        return (new MailMessage)
                    ->subject('Your Order Has Been ' . $status)
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your order (ID: ' . $this->order->id . ') has been ' . $this->order->status . '.')
                    ->action('View Order', url('/dashboard'))
                    ->line('Thank you for ordering with us!');
    }
}
