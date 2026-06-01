<?php

namespace App\Http\Controllers;

use App\Models\EmergencyContact;
use Illuminate\Http\Request;

class EmergencyContactController extends Controller
{
    public function index()
    {
        $contacts = EmergencyContact::latest()->paginate(10);
        return view('speedsos.contacts', compact('contacts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'type' => 'required|in:ambulans,polisi,pemadam,bencana,lainnya',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        EmergencyContact::create($validated);
        return redirect()->route('admin.contacts.index')->with('success', 'Kontak darurat ditambahkan');
    }

    public function update(Request $request, EmergencyContact $contact)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'type' => 'sometimes|in:ambulans,polisi,pemadam,bencana,lainnya',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        $contact->update($validated);
        return redirect()->route('admin.contacts.index')->with('success', 'Kontak darurat diperbarui');
    }

    public function destroy(EmergencyContact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Kontak darurat dihapus');
    }
}
