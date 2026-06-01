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
        $this->authorizeResource(QueueTicket::class, 'ticket');
    }

    public function index()
    {
        $tickets = QueueTicket::with(['serviceSlot.service.institution', 'user'])
            ->latest()
            ->paginate(15);
        return view('speedq.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('speedq.tickets.create');
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
