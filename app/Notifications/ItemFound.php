<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ItemFound extends Notification
{
    use Queueable;

    public $finderName;
    public $itemName;

    /**
     * Create a new notification instance.
     */
    public function __construct($finderName, $itemName)
    {
        $this->finderName = $finderName;
        $this->itemName = $itemName;
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
            'title' => 'Barang Ditemukan!',
            'message' => $this->finderName . ' melaporkan menemukan barang yang mungkin milik Anda: ' . $this->itemName,
            'url' => route('lost-found.index'),
        ];
    }
}
