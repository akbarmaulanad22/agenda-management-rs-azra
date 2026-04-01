<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy("full_name")->paginate(15);

        return view("admin.employees.index", compact("employees"));
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
            "organization" => "required|string|max:255",
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
        return view("admin.employees.edit", compact("employee"));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            "nip" =>
                "required|string|max:255|unique:employees,nip," . $employee->id,
            "full_name" => "required|string|max:255",
            "organization" => "required|string|max:255",
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
}
