<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSlotRequest;
use App\Models\Service;
use App\Models\ServiceSlot;
use App\Services\QueueService;

class ServiceSlotController extends Controller
{
    public function __construct(
        protected QueueService $queueService
    ) {}
    public function index(Service $service)
    {
        $slots = $service->serviceSlots()
            ->whereDate('date', '>=', now())
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate(10);
        return view('speedq.slots.index', compact('service', 'slots'));
    }

    public function create(Service $service)
    {
        return view('speedq.slots.create', compact('service'));
    }

    public function store(StoreSlotRequest $request)
    {
        $data = $request->validated();
        ServiceSlot::create($data);
        return redirect()->route('admin.slots.index', $data['service_id'])
            ->with('success', 'Slot berhasil ditambahkan');
    }

    public function destroy(ServiceSlot $slot)
    {
        $serviceId = $slot->service_id;
        $slot->delete();
        return redirect()->route('admin.slots.index', $serviceId)
            ->with('success', 'Slot berhasil dihapus');
    }

    public function availableSlots(\Illuminate\Http\Request $request, Service $service)
    {
        $date = $request->query('date', now()->toDateString());
        $slots = $this->queueService->getAvailableSlots($service, $date);
        return response()->json($slots);
    }
}
