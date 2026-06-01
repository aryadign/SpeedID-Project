<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstitutionRequest;
use App\Http\Requests\UpdateInstitutionRequest;
use App\Models\Institution;
use App\Models\Subdistrict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstitutionController extends Controller
{
    public function index()
    {
        $institutions = Institution::with('subdistrict.district.province')
            ->latest()
            ->paginate(10);
        return view('speedq.institutions.index', compact('institutions'));
    }

    public function create()
    {
        $subdistricts = Subdistrict::with('district.province')->get();
        return view('speedq.institutions.create', compact('subdistricts'));
    }

    public function store(StoreInstitutionRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('institutions', 'public');
        }
        Institution::create($data);
        return redirect()->route('admin.institutions.index')->with('success', 'Instansi berhasil ditambahkan');
    }

    public function edit(Institution $institution)
    {
        $subdistricts = Subdistrict::with('district.province')->get();
        return view('speedq.institutions.edit', compact('institution', 'subdistricts'));
    }

    public function update(UpdateInstitutionRequest $request, Institution $institution)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            if ($institution->photo) Storage::disk('public')->delete($institution->photo);
            $data['photo'] = $request->file('photo')->store('institutions', 'public');
        }
        $institution->update($data);
        return redirect()->route('admin.institutions.index')->with('success', 'Instansi berhasil diperbarui');
    }

    public function destroy(Institution $institution)
    {
        if ($institution->photo) Storage::disk('public')->delete($institution->photo);
        $institution->delete();
        return redirect()->route('admin.institutions.index')->with('success', 'Instansi berhasil dihapus');
    }
}
