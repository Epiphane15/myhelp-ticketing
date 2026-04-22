<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TicketMessageController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Ticket $ticket)
    {
        $this->authorize('reply', $ticket);

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $ticket->messages()->create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        $user = Auth::user();
        if ($user->role === 'agent' || $user->role === 'admin') {
            $ticket->update(['status' => 'en_attente']); // waiting for client
        } else {
            $ticket->update(['status' => 'en_cours']); // client replied
        }

        return back()->with('success', 'Message envoyé.');
    }
}
