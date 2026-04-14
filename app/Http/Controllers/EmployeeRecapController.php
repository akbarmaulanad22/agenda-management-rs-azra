<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeRecapController extends Controller
{
    public function index(Request $request)
    {
        $units = Unit::orderBy('name')->get();

        $employees = $this->buildOrderedAggregateRecapQuery($request)
            ->paginate(15)
            ->withQueryString();

        $summaryRow = DB::query()
            ->fromSub($this->buildAggregateRecapQuery($request), 'employee_recaps')
            ->selectRaw('COUNT(*) as employee_count')
            ->selectRaw('COALESCE(SUM(attendance_count), 0) as attendance_count')
            ->selectRaw('COALESCE(SUM(rapat_hours), 0) as rapat_hours')
            ->selectRaw('COALESCE(SUM(training_hours), 0) as training_hours')
            ->first();

        $summary = [
            'employee_count' => (int) ($summaryRow->employee_count ?? 0),
            'attendance_count' => (int) ($summaryRow->attendance_count ?? 0),
            'rapat_hours' => round((float) ($summaryRow->rapat_hours ?? 0), 2),
            'training_hours' => round((float) ($summaryRow->training_hours ?? 0), 2),
        ];

        return view('admin.employee-recaps.index', compact('employees', 'summary', 'units'));
    }

    public function exportCsv(Request $request)
    {
        $filename = 'rekap-karyawan-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($request) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'NIP',
                'Nama Lengkap',
                'Unit',
                'Jabatan',
                'Total Ikut Agenda',
                'Jam Rapat',
                'Jam Diklat/Pelatihan',
            ]);

            foreach ($this->buildOrderedAggregateRecapQuery($request)->cursor() as $row) {
                fputcsv($file, [
                    $row->nip,
                    $row->full_name,
                    $row->unit_name ?? '-',
                    $row->job_position,
                    (int) $row->attendance_count,
                    number_format((float) $row->rapat_hours, 2, '.', ''),
                    number_format((float) $row->training_hours, 2, '.', ''),
                ]);
            }

            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function buildOrderedAggregateRecapQuery(Request $request): QueryBuilder
    {
        return $this->buildAggregateRecapQuery($request)
            ->orderByDesc('attendance_count')
            ->orderBy('full_name');
    }

    private function buildAggregateRecapQuery(Request $request): QueryBuilder
    {
        $search = trim((string) $request->input('search'));
        $durationHoursSql = $this->durationHoursExpression();
        $searchOperator = $this->searchOperator();

        return DB::table('employees')
            ->leftJoin('units', 'units.id', '=', 'employees.unit_id')
            ->leftJoin('agenda_employee', function (JoinClause $join) {
                $join->on('agenda_employee.employee_id', '=', 'employees.id')
                    ->whereNotNull('agenda_employee.signature_image_path');
            })
            ->leftJoin('agendas', function (JoinClause $join) use ($request) {
                $join->on('agendas.id', '=', 'agenda_employee.agenda_id');
                $this->applyAggregateAgendaFilters($join, $request);
            })
            ->select([
                'employees.id',
                'employees.nip',
                'employees.full_name',
                'employees.profession',
                'employees.job_position',
                'units.name as unit_name',
            ])
            ->selectRaw('COUNT(DISTINCT agendas.id) as attendance_count')
            ->selectRaw("COALESCE(SUM(CASE WHEN agendas.type = 'rapat' THEN {$durationHoursSql} ELSE 0 END), 0) as rapat_hours")
            ->selectRaw("COALESCE(SUM(CASE WHEN agendas.type IN ('diklat', 'pelatihan') THEN {$durationHoursSql} ELSE 0 END), 0) as training_hours")
            ->when($search !== '', function (QueryBuilder $query) use ($search, $searchOperator) {
                $query->where(function (QueryBuilder $query) use ($search, $searchOperator) {
                    $query->where('employees.full_name', $searchOperator, "%{$search}%")
                        ->orWhere('employees.nip', $searchOperator, "%{$search}%")
                        ->orWhere('employees.job_position', $searchOperator, "%{$search}%")
                        ->orWhere('units.name', $searchOperator, "%{$search}%");
                });
            })
            ->when($request->filled('unit_id'), function (QueryBuilder $query) use ($request) {
                $query->where('employees.unit_id', $request->integer('unit_id'));
            })
            ->groupBy(
                'employees.id',
                'employees.nip',
                'employees.full_name',
                'employees.profession',
                'employees.job_position',
                'units.name'
            );
    }

    private function applyAggregateAgendaFilters(JoinClause $join, Request $request): void
    {
        $join
            ->when($request->filled('date_from'), function (JoinClause $join) use ($request) {
                $join->where('agendas.event_date', '>=', $request->input('date_from'));
            })
            ->when($request->filled('date_to'), function (JoinClause $join) use ($request) {
                $join->where('agendas.event_date', '<=', $request->input('date_to'));
            });
    }

    private function durationHoursExpression(): string
    {
        return match (DB::connection()->getDriverName()) {
            'pgsql' => "CASE WHEN agendas.event_end_time IS NOT NULL AND agendas.event_end_time > agendas.event_time THEN EXTRACT(EPOCH FROM (agendas.event_end_time - agendas.event_time)) / 3600 ELSE 0 END",
            'sqlite' => "CASE WHEN agendas.event_end_time IS NOT NULL AND agendas.event_end_time > agendas.event_time THEN (julianday('2000-01-01 ' || agendas.event_end_time) - julianday('2000-01-01 ' || agendas.event_time)) * 24 ELSE 0 END",
            default => "CASE WHEN agendas.event_end_time IS NOT NULL AND agendas.event_end_time > agendas.event_time THEN TIMESTAMPDIFF(MINUTE, agendas.event_time, agendas.event_end_time) / 60 ELSE 0 END",
        };
    }

    private function searchOperator(): string
    {
        return DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
    }
}
