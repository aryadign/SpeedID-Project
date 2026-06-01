<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSOSRequest;
use App\Models\EmergencyContact;
use App\Models\SOSRequest;
use App\Services\SOSService;
use Illuminate\Http\Request;

class SOSController extends Controller
{
    public function __construct(
        protected SOSService $sosService
    ) {
        $this->authorizeResource(SOSRequest::class, 'sos');
    }

    public function index()
    {
        $contacts = EmergencyContact::where('is_active', true)->get();
        return view('speedsos.index', compact('contacts'));
    }

    public function store(StoreSOSRequest $request)
    {
        $sos = $this->sosService->createSOSRequest($request->user(), $request->validated());
        return redirect()->route('sos.show', $sos->id)
            ->with('success', 'Permintaan darurat telah dikirim');
    }

    public function show(SOSRequest $sos)
    {
        $sos->load('user');
        return view('speedsos.show', compact('sos'));
    }

    public function updateStatus(Request $request, SOSRequest $sos)
    {
        $validated = $request->validate([
            'status' => 'required|in:masuk,diproses,selesai,ditolak',
        ]);

        $this->sosService->updateStatus($sos, $validated['status']);

        return back()->with('success', 'Status SOS diperbarui');
    }

    public function active()
    {
        $active = $this->sosService->getActiveSOS();
        return response()->json($active);
    }

    public function adminMonitoring()
    {
        $activeSOS = SOSRequest::with('user')
            ->whereIn('status', ['masuk', 'diproses'])
            ->latest()
            ->get();
        return view('speedsos.admin-monitoring', compact('activeSOS'));
    }
}
