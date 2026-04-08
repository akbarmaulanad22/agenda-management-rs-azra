<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\AgendaQuestionAnswer;
use App\Models\Employee;
use Illuminate\Http\Request;

class PublicQuizController extends Controller
{
    public function show(Agenda $agenda)
    {
        abort_unless($agenda->status === 'active', 404);
        abort_if($agenda->agendaQuestions()->count() === 0, 404);

        $agenda->load(['room', 'agendaQuestions']);

        $employees = Employee::orderBy('full_name')->get();

        $employeesJson = $employees->map(fn ($e) => [
            'id' => $e->id,
            'name' => $e->full_name,
            'position' => $e->job_position,
            'organization' => $e->organization,
        ]);

        $questionsJson = $agenda->agendaQuestions->map(fn ($q) => [
            'id' => $q->id,
            'question_text' => $q->question_text,
            'option_a' => $q->option_a,
            'option_b' => $q->option_b,
            'option_c' => $q->option_c,
            'option_d' => $q->option_d,
            'option_e' => $q->option_e,
        ]);

        // Employee IDs that already completed the quiz
        $completedEmployeeIds = AgendaQuestionAnswer::where('agenda_id', $agenda->id)
            ->select('employee_id')
            ->distinct()
            ->pluck('employee_id');

        return view('attendance.quiz', compact(
            'agenda',
            'employeesJson',
            'questionsJson',
            'completedEmployeeIds',
        ));
    }

    public function store(Request $request, Agenda $agenda)
    {
        abort_unless($agenda->status === 'active', 404);
        abort_if($agenda->agendaQuestions()->count() === 0, 404);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'answers' => 'required|array',
            'answers.*' => 'required|in:a,b,c,d,e',
        ]);

        $employeeId = $request->employee_id;

        // Check if already answered
        $existing = AgendaQuestionAnswer::where('agenda_id', $agenda->id)
            ->where('employee_id', $employeeId)
            ->exists();

        if ($existing) {
            return response()->json([
                'message' => 'Anda sudah mengerjakan soal ini.',
            ], 422);
        }

        $questions = $agenda->agendaQuestions()->get()->keyBy('id');
        $correct = 0;
        $total = $questions->count();
        $rows = [];

        foreach ($request->answers as $questionId => $selectedOption) {
            $question = $questions->get($questionId);
            if (!$question) continue;

            $isCorrect = $question->correct_option === $selectedOption;
            if ($isCorrect) $correct++;

            $rows[] = [
                'agenda_id' => $agenda->id,
                'employee_id' => $employeeId,
                'agenda_question_id' => $question->id,
                'selected_option' => $selectedOption,
                'is_correct' => $isCorrect,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        AgendaQuestionAnswer::insert($rows);

        return response()->json([
            'message' => 'Jawaban berhasil disimpan.',
            'correct' => $correct,
            'total' => $total,
        ]);
    }
}
