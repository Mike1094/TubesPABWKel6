<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrafficInfo extends Notification
{
    use Queueable;

    public $trafficUpdate;

    /**
     * Create a new notification instance.
     */
    public function __construct($trafficUpdate)
    {
        $this->trafficUpdate = $trafficUpdate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Update Lalu Lintas: ' . $this->trafficUpdate->status,
            'message' => 'Lokasi: ' . $this->trafficUpdate->location . '. ' . $this->trafficUpdate->description,
            'url' => route('traffic.index'),
        ];
    }
}
