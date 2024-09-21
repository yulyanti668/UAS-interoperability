<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;

class EmployeeController extends Controller{

    // Tirta Subagja - D112021114
    public function index()
    {
        $employees = Employee::with(['user', 'department', 'position'])->get();
        return response()->json($employees);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'department_id' => 'required|integer|exists:departments,id',
            'position_id' => 'required|integer|exists:positions,id',
            'hire_date' => 'required|date',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => "karyawan",
            'password' => app('hash')->make($request->password),
        ]);

        $employee = Employee::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'department_id' => $request->department_id,
            'position_id' => $request->position_id,
            'hire_date' => $request->hire_date,
        ]);

        return response()->json(['employee' => $employee, 'user' => $user], 201);
    }

    public function show($id)
    {
        $employee = Employee::with(['user', 'department', 'position'])->find($id);
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }
        return response()->json($employee);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id); 
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $user = $employee->user;
        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'role' => "karyawan",
            'password' => $request->password ? app('hash')->make($request->password) : $user->password,
        ]);

        $employee->update([
            'name' => $request->name ?? $user->name,
            'department_id' => $request->department_id ?? $employee->department_id,
            'position_id' => $request->position_id ?? $employee->position_id,
            'hire_date' => $request->hire_date ?? $employee->hire_date,
        ]);

        return response()->json($employee);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $user = $employee->user;
        $employee->delete();   
        $user->delete();       

        return response()->json(['message' => 'Employee and user deleted successfully']);
    }
    
}