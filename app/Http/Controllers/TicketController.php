<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Priority;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TicketController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Ticket::with(['user', 'agent', 'priority'])->latest();

        if ($user->role === 'agent') {
            $query->where(function($q) use ($user) {
                $q->where('agent_id', $user->id)->orWhereNull('agent_id');
            });
        } elseif ($user->role === 'client') {
            $query->where('user_id', $user->id);
        }

        // Filters for internal staff
        if ($user->role !== 'client') {
            if ($request->filled('priority_id')) {
                $query->where('priority_id', $request->input('priority_id'));
            }
            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }
        }

        $tickets = $query->get();
        $priorities = Priority::all();
        return view('tickets.index', compact('tickets', 'priorities'));
    }

    public function create()
    {
        $this->authorize('create', Ticket::class);
        $priorities = Priority::all();
        return view('tickets.create', compact('priorities'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Ticket::class);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority_id' => 'required|exists:priorities,id',
        ]);

        $ticket = Auth::user()->tickets()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority_id' => $validated['priority_id'],
            'status' => 'ouvert',
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket créé avec succès.');
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        $ticket->load(['user', 'agent', 'priority', 'messages.user', 'attachments']);
        $agents = User::where('role', 'agent')->get();
        return view('tickets.show', compact('ticket', 'agents'));
    }

    public function edit(Ticket $ticket)
    {
        // Not used, mostly inline updating
    }

    public function update(Request $request, Ticket $ticket)
    {
        // generic updates
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);
        $ticket->update(['is_active' => false]);
        return redirect()->route('tickets.index')->with('success', 'Ticket supprimé (Archivé).');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        $validated = $request->validate(['agent_id' => 'nullable|exists:users,id']);
        
        $ticket->update([
            'agent_id' => $validated['agent_id'],
            'status' => 'assigne',
        ]);

        return back()->with('success', 'Ticket assigné avec succès.');
    }

    public function close(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        $ticket->update(['status' => 'resolu']);
        
        \Illuminate\Support\Facades\Notification::send($ticket->user, new \App\Notifications\TicketResolvedNotification($ticket));
        
        return back()->with('success', 'Ticket fermé avec succès et le client a été notifié.');
    }
}
