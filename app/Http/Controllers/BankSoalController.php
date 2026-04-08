<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use Illuminate\Http\Request;

class BankSoalController extends Controller
{
    public function index()
    {
        $bankSoals = BankSoal::withCount('questions')->latest()->paginate(15);

        return view('admin.bank-soals.index', compact('bankSoals'));
    }

    public function create()
    {
        return view('admin.bank-soals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.option_a' => 'required|string|max:255',
            'questions.*.option_b' => 'required|string|max:255',
            'questions.*.option_c' => 'required|string|max:255',
            'questions.*.option_d' => 'required|string|max:255',
            'questions.*.option_e' => 'required|string|max:255',
            'questions.*.correct_option' => 'required|in:a,b,c,d,e',
        ]);

        $bankSoal = BankSoal::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        $bankSoal->questions()->createMany($validated['questions']);

        return redirect()
            ->route('admin.bank-soals.index')
            ->with('success', 'Bank soal berhasil ditambahkan.');
    }

    public function show(BankSoal $bankSoal)
    {
        $bankSoal->load('questions');

        return view('admin.bank-soals.show', compact('bankSoal'));
    }

    public function edit(BankSoal $bankSoal)
    {
        $bankSoal->load('questions');

        return view('admin.bank-soals.edit', compact('bankSoal'));
    }

    public function update(Request $request, BankSoal $bankSoal)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.option_a' => 'required|string|max:255',
            'questions.*.option_b' => 'required|string|max:255',
            'questions.*.option_c' => 'required|string|max:255',
            'questions.*.option_d' => 'required|string|max:255',
            'questions.*.option_e' => 'required|string|max:255',
            'questions.*.correct_option' => 'required|in:a,b,c,d,e',
        ]);

        $bankSoal->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        $bankSoal->questions()->delete();
        $bankSoal->questions()->createMany($validated['questions']);

        return redirect()
            ->route('admin.bank-soals.index')
            ->with('success', 'Bank soal berhasil diperbarui.');
    }

    public function destroy(BankSoal $bankSoal)
    {
        $bankSoal->delete();

        return redirect()
            ->route('admin.bank-soals.index')
            ->with('success', 'Bank soal berhasil dihapus.');
    }
}
