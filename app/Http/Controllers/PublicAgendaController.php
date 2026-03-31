<?php

namespace App\Http\Controllers;

use App\Models\Agenda;

class PublicAgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::where('status', 'active')
            ->whereDate('event_date', today())
            ->withCount([
                'participants',
                'participants as signed_count' => function ($query) {
                    $query->whereNotNull('agenda_participant.signed_at');
                },
            ])
            ->orderBy('event_time')
            ->get();

        return view('public.agenda-today', compact('agendas'));
    }
}
