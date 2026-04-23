<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TicketRepliedNotification extends Notification
{
    use Queueable;
    public $ticket;

    public function __construct(Ticket $ticket) { $this->ticket = $ticket; }
    public function via($notifiable) { return ['database']; }
    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => 'Nouvelle Réponse',
            'message' => 'L\'agent vous a répondu sur le ticket #' . $this->ticket->id
        ];
    }
}
