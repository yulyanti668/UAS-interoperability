<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;

class AdminController extends Controller{
    // Yulyanti - D112331025
    public function index()
    {
        $admins = Admin::with('user')->get();
        return response()->json($admins);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => "admin",
            'password' => app('hash')->make($request->password),
        ]);

        $admin = Admin::create([
            'user_id' => $user->id,
            'name' => $user->name,

        ]);

        return response()->json(['admin' => $admin, 'user' => $user], 201);
    }

    public function show($id)
    {
        $admin = Admin::with('user')->find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }
        return response()->json($admin);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        $user = $admin->user;
        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'role' => "admin",
            'password' => $request->password ? app('hash')->make($request->password) : $user->password,
        ]);

        $admin->update([
            'name' => $user->name,
        ]);

        return response()->json($admin);
    }

    public function destroy($id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        $user = $admin->user;
        $admin->delete();  
        $user->delete();   

        return response()->json(['message' => 'Admin and user deleted successfully']);
    }
}