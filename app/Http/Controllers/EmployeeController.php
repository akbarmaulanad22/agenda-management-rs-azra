<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function search(Request $request)
    {
        if ($request->filled('id')) {
            $employee = Employee::find($request->id);

            return response()->json([
                'items' => $employee ? [[
                    'id' => $employee->id,
                    'name' => $employee->full_name,
                ]] : [],
                'has_more' => false,
            ]);
        }

        $search = trim((string) $request->input('q'));
        $operator = $this->searchOperator();

        $employees = Employee::query()
            ->orderBy('full_name')
            ->when($search !== '', function ($query) use ($search, $operator) {
                $query->where(function ($query) use ($search, $operator) {
                    $query->where('full_name', $operator, "%{$search}%")
                        ->orWhere('nip', $operator, "%{$search}%");
                });
            })
            ->simplePaginate(10);

        return response()->json([
            'items' => collect($employees->items())
                ->map(fn (Employee $employee) => [
                    'id' => $employee->id,
                    'name' => $employee->full_name,
                ])
                ->values(),
            'has_more' => $employees->hasMorePages(),
        ]);
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $unitId = $request->input('unit_id');
        $operator = $this->searchOperator();

        $employees = Employee::with('unit')
            ->orderBy('full_name')
            ->when($q !== '', fn ($query) => $query->where(function ($query) use ($q, $operator) {
                $query->where('full_name', $operator, "%{$q}%")
                    ->orWhere('nip', $operator, "%{$q}%");
            }))
            ->when($unitId, fn ($query) => $query->where('unit_id', $unitId))
            ->paginate(15)
            ->withQueryString();

        $selectedUnit = $unitId ? Unit::find($unitId) : null;

        return view("admin.employees.index", compact("employees", "q", "selectedUnit"));
    }

    public function create()
    {
        return view("admin.employees.create");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "nip" => "required|string|max:255|unique:employees,nip",
            "full_name" => "required|string|max:255",
            "unit_id" => "required|exists:units,id",
            "job_position" => "required|string|max:255",
            "structural_role" => "required|string|max:255",
            "profession" => "required|string|max:255",
        ]);

        Employee::create($validated);

        return redirect()
            ->route("admin.employees.index")
            ->with("success", "Pegawai berhasil ditambahkan.");
    }

    public function edit(Employee $employee)
    {
        $employee->load('unit');

        return view("admin.employees.edit", compact("employee"));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            "nip" =>
                "required|string|max:255|unique:employees,nip," . $employee->id,
            "full_name" => "required|string|max:255",
            "unit_id" => "required|exists:units,id",
            "job_position" => "required|string|max:255",
            "structural_role" => "required|string|max:255",
            "profession" => "required|string|max:255",
        ]);

        $employee->update($validated);

        return redirect()
            ->route("admin.employees.index")
            ->with("success", "Pegawai berhasil diperbarui.");
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()
            ->route("admin.employees.index")
            ->with("success", "Pegawai berhasil dihapus.");
    }

    private function searchOperator(): string
    {
        return DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
    }
}
