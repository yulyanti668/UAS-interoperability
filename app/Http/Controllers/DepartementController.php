<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departement;

class DepartementController extends Controller{
    // Yulyanti - D112331025
    public function index()
    {
        $departments = Departement::all();
        return response()->json($departments);
    }
 
    public function store(Request $request)
    { 
        $this->validate($request, [
            'name' => 'required|string|unique:departments',
        ]);
 
        $department = Departement::create([
            'name' => $request->name,
        ]);

        return response()->json($department, 201);
    }
 
    public function show($id)
    {
        $department = Departement::find($id);
        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }
        return response()->json($department);
    }
 
    public function update(Request $request, $id)
    {
        $department = Departement::find($id);
        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        } 

        $department->update([
            'name' => $request->name ?? $department->name,
        ]);

        return response()->json($department);
    }
 
    public function destroy($id)
    {
        $department = Departement::find($id);
        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        $department->delete();
        return response()->json(['message' => 'Department deleted successfully']);
    }
}