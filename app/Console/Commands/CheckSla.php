<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Models\User;
use App\Models\SlaLog;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Notifications\SlaBreachedNotification;
use Illuminate\Support\Facades\Notification;

class CheckSla extends Command
{
    protected $signature = 'tickets:check-sla';
    protected $description = 'Check for SLA breaches on unresolved tickets';

    public function handle()
    {
        $limit = Carbon::now()->subHours(2);
        
        $breachedTickets = Ticket::whereIn('status', ['ouvert', 'assigne', 'en_attente'])
            ->where('created_at', '<', $limit)
            ->whereDoesntHave('slaLogs')
            ->get();

        if ($breachedTickets->isEmpty()) {
            $this->info('No SLA breaches found.');
            return;
        }

        $admins = User::where('role', 'admin')->get();

        foreach ($breachedTickets as $ticket) {
            SlaLog::create([
                'ticket_id' => $ticket->id,
                'breached_at' => now(),
                'notified' => true
            ]);

            $notifiables = collect($admins);
            if ($ticket->agent_id) {
                $notifiables->push($ticket->agent);
            } else {
                $agents = User::where('role', 'agent')->get();
                $notifiables = $notifiables->merge($agents);
            }

            Notification::send($notifiables->unique('id'), new SlaBreachedNotification($ticket));
            
            $this->info("Ticket #{$ticket->id} SLA breached.");
        }
    }
}
