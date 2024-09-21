<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller{
    
    // Yulyanti - D112331025
    public function index()
    {
        $positions = Position::all();
        return response()->json($positions);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|unique:positions',
        ]);

        $position = Position::create([
            'title' => $request->title,
        ]);

        return response()->json($position, 201);
    }

    public function show($id)
    {
        $position = Position::find($id);
        if (!$position) {
            return response()->json(['message' => 'Position not found'], 404);
        }
        return response()->json($position);
    }

    public function update(Request $request, $id)
    {
        $position = Position::find($id);
        if (!$position) {
            return response()->json(['message' => 'Position not found'], 404);
        }

        $position->update([
            'title' => $request->title ?? $position->title,
        ]);

        return response()->json($position);
    }

    public function destroy($id)
    {
        $position = Position::find($id);
        if (!$position) {
            return response()->json(['message' => 'Position not found'], 404);
        }

        $position->delete();
        return response()->json(['message' => 'Position deleted successfully']);
    }
}