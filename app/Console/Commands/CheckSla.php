<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Models\User;
use App\Models\SlaLog;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Notifications\SlaBreachedNotification;
use App\Notifications\SlaWarningNotification;
use Illuminate\Support\Facades\Notification;

class CheckSla extends Command
{
    protected $signature = 'tickets:check-sla';
    protected $description = 'Check for SLA warnings and breaches on unresolved tickets';

    public function handle()
    {
        $now = Carbon::now();
        // 1h30
        $warningLimit = $now->copy()->subMinutes(3);
        // 2h00
        $breachLimit = $now->copy()->subMinutes(5);
        
        $admins = User::where('role', 'admin')->get();

        // --- 1. AVERTISSEMENTS (Warning) ---
        // Tickets créés avant 1h30 mais non-éligibles au breach direct (pas encore 2h)
        $warningTickets = Ticket::where('is_active', true)
            ->whereIn('status', ['ouvert', 'assigne', 'en_attente'])
            ->where('created_at', '<=', $warningLimit)
            ->where('created_at', '>', $breachLimit)
            ->whereDoesntHave('slaLogs', function($query) {
                $query->where('type', 'warning');
            })
            ->get();

        foreach ($warningTickets as $ticket) {
            // Créer le log pour ne pas spammer
            SlaLog::create([
                'ticket_id' => $ticket->id,
                'type' => 'warning',
                'breached_at' => now(),
                'notified' => true
            ]);
            
            // Notifier l'agent ou les agents (si aucun n'est assigné)
            $notifiables = collect();
            if ($ticket->agent_id) {
                $notifiables->push($ticket->agent);
            } else {
                $notifiables = User::where('role', 'agent')->get();
            }

            Notification::send($notifiables->unique('id'), new SlaWarningNotification($ticket));
            $this->info("Ticket #{$ticket->id} SLA warning sent.");
        }

        // --- 2. BREACH & SUPPRESSION (2h00) ---
        $breachedTickets = Ticket::where('is_active', true)
            ->whereIn('status', ['ouvert', 'assigne', 'en_attente'])
            ->where('created_at', '<=', $breachLimit)
            ->whereDoesntHave('slaLogs', function($query) {
                $query->where('type', 'breach');
            })
            ->get();

        foreach ($breachedTickets as $ticket) {
            SlaLog::create([
                'ticket_id' => $ticket->id,
                'type' => 'breach',
                'breached_at' => now(),
                'notified' => true
            ]);
            
            // Soft delete logique du ticket
            $ticket->update(['is_active' => false]);
            
            // Notifier EXCLUSIVEMENT les admins
            Notification::send($admins, new SlaBreachedNotification($ticket));
            
            $this->info("Ticket #{$ticket->id} deleted (is_active = 0) due to SLA breach.");
        }
    }
}
