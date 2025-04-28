<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reservation;

class ReservationStatusNotification extends Notification
{
    use Queueable;

    protected $reservation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
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
        $status = ucfirst($this->reservation->status);

        return (new MailMessage)
                    ->subject('Your Reservation Has Been ' . $status)
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your reservation (ID: ' . $this->reservation->id . ') has been ' . $this->reservation->status . '.')
                    ->action('View Reservation', url('/dashboard'))
                    ->line('Thank you for choosing our restaurant!');
    }
}
