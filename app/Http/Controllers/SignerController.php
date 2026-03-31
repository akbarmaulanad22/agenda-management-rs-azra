<?php

namespace App\Http\Controllers;

use App\Models\Signer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SignerController extends Controller
{
    public function index()
    {
        $signers = Signer::latest()->paginate(10);
        return view('admin.signers.index', compact('signers'));
    }

    public function create()
    {
        return view('admin.signers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'signature_file' => 'nullable|image|mimes:png|max:2048',
        ]);

        if ($request->hasFile('signature_file')) {
            $validated['signature_path'] = $request->file('signature_file')
                ->store('signer-signatures', 'public');
        }

        unset($validated['signature_file']);
        Signer::create($validated);

        return redirect()->route('admin.signers.index')
            ->with('success', 'Penandatangan berhasil ditambahkan.');
    }

    public function edit(Signer $signer)
    {
        return view('admin.signers.edit', compact('signer'));
    }

    public function update(Request $request, Signer $signer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'signature_file' => 'nullable|image|mimes:png|max:2048',
        ]);

        if ($request->hasFile('signature_file')) {
            if ($signer->signature_path) {
                Storage::disk('public')->delete($signer->signature_path);
            }
            $validated['signature_path'] = $request->file('signature_file')
                ->store('signer-signatures', 'public');
        }

        unset($validated['signature_file']);
        $signer->update($validated);

        return redirect()->route('admin.signers.index')
            ->with('success', 'Penandatangan berhasil diperbarui.');
    }

    public function destroy(Signer $signer)
    {
        if ($signer->signature_path) {
            Storage::disk('public')->delete($signer->signature_path);
        }
        $signer->delete();

        return redirect()->route('admin.signers.index')
            ->with('success', 'Penandatangan berhasil dihapus.');
    }
}
