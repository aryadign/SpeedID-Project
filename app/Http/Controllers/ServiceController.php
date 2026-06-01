<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Institution;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index(Institution $institution)
    {
        $services = $institution->services()->latest()->paginate(10);
        return view('speedq.services.index', compact('institution', 'services'));
    }

    public function create(Institution $institution)
    {
        return view('speedq.services.create', compact('institution'));
    }

    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();
        Service::create($data);
        return redirect()->route('admin.services.index', $data['institution_id'])
            ->with('success', 'Layanan berhasil ditambahkan');
    }

    public function edit(Service $service)
    {
        return view('speedq.services.edit', compact('service'));
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update($request->validated());
        return redirect()->route('admin.services.index', $service->institution_id)
            ->with('success', 'Layanan berhasil diperbarui');
    }

    public function destroy(Service $service)
    {
        $institutionId = $service->institution_id;
        $service->delete();
        return redirect()->route('admin.services.index', $institutionId)
            ->with('success', 'Layanan berhasil dihapus');
    }
}
