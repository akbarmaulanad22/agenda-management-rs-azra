<?php

namespace App\Http\Controllers;

use App\Models\InvitationTemplate;
use Illuminate\Http\Request;

class InvitationTemplateController extends Controller
{
    public function index()
    {
        $templates = InvitationTemplate::latest()->paginate(10);
        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'body_content' => 'required|string',
        ]);

        InvitationTemplate::create($validated);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template undangan berhasil ditambahkan.');
    }

    public function edit(InvitationTemplate $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    public function update(Request $request, InvitationTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'body_content' => 'required|string',
        ]);

        $template->update($validated);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template undangan berhasil diperbarui.');
    }

    public function destroy(InvitationTemplate $template)
    {
        $template->delete();

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template undangan berhasil dihapus.');
    }
}
