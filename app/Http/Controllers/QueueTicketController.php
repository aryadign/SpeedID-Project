<?php

namespace App\Http\Controllers;

use App\Models\QueueTicket;
use App\Models\Service;
use App\Models\ServiceSlot;
use App\Services\QueueService;
use Illuminate\Http\Request;

class QueueTicketController extends Controller
{
    public function __construct(
        protected QueueService $queueService
    ) {
        // Exclude 'cancel' so it doesn't get checked against the 'update' policy (admin-only).
        // Access control for cancel() is enforced manually inside the method.
        $this->authorizeResource(QueueTicket::class, 'ticket', [
            'except' => ['cancel'],
        ]);
    }

    public function index(Request $request)
    {
        $query = QueueTicket::with(['serviceSlot.service.institution', 'user']);

        if (!$request->routeIs('admin.*')) {
            $query->where('user_id', $request->user()->id);
        }

        $tickets = $query->latest()->paginate(15);
        return view('speedq.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $institutions = \App\Models\Institution::where('is_active', true)->get();
        return view('speedq.tickets.create', compact('institutions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_slot_id' => 'required|exists:service_slots,id',
        ]);

        $slot = ServiceSlot::findOrFail($validated['service_slot_id']);

        $bookedCount = $slot->queueTickets()->whereDate('created_at', today())->count();
        if ($bookedCount >= $slot->quota) {
            return back()->withErrors(['service_slot_id' => 'Kuota slot ini sudah penuh.']);
        }

        $ticket = $this->queueService->createBooking(
            $request->user(),
            $slot
        );

        return redirect()->route('queue.tickets.show', $ticket->id)
            ->with('success', 'Antrian berhasil dibuat. Nomor antrian: ' . $ticket->queue_number);
    }

    public function show(QueueTicket $ticket)
    {
        $ticket->load(['serviceSlot.service.institution', 'user']);
        return view('speedq.tickets.show', compact('ticket'));
    }

    public function cancel(Request $request, QueueTicket $ticket)
    {
        $this->authorize('cancel', $ticket);

        if (!in_array($ticket->status, ['menunggu', 'dipanggil'])) {
            return back()->withErrors(['ticket' => 'Antrean ini tidak dapat dibatalkan.']);
        }

        $this->queueService->cancel($ticket);

        return redirect()->route('queue.tickets')
            ->with('success', 'Antrean #' . $ticket->queue_number . ' telah dibatalkan.');
    }

    public function current(Service $service)
    {
        $slot = $service->serviceSlots()
            ->whereDate('date', now()->toDateString())
            ->first();

        $current = $slot ? $this->queueService->currentQueue($slot) : collect();
        return response()->json($current);
    }

    public function apiStatus(QueueTicket $ticket)
    {
        return response()->json([
            'status' => $ticket->status,
            'queue_number' => $ticket->queue_number,
            'estimated_time' => $ticket->estimated_time?->format('H:i'),
            'updated_at' => $ticket->updated_at,
        ]);
    }

    public function display(Service $service)
    {
        $slot = $service->serviceSlots()
            ->whereDate('date', now()->toDateString())
            ->first();

        $current = $slot ? $this->queueService->currentQueue($slot) : collect();
        return view('speedq.display', compact('service', 'current'));
    }
}
