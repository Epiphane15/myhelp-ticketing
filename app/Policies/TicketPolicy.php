<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->role === 'admin') return true;
        if ($user->role === 'agent') {
            return $ticket->agent_id === $user->id || is_null($ticket->agent_id);
        }
        return $ticket->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'client';
    }

    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->role === 'admin') return true;
        if ($user->role === 'agent') {
            return $ticket->agent_id === $user->id || is_null($ticket->agent_id);
        }
        return false;
    }

    public function reply(User $user, Ticket $ticket): bool
    {
        if ($user->role === 'admin') return true;
        if ($user->role === 'agent') return $ticket->agent_id === $user->id;
        return $ticket->user_id === $user->id;
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->role === 'admin';
    }
}
