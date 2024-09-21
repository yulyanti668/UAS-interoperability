<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Employee;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    // Tirta Subagja - D112021114
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->role == 'admin') {
            $admin = Admin::where('user_id', $user->id)->first();
            $admin = Admin::with('user')->find($admin->id);
            if (!$admin) {
                return response()->json(['message' => 'Admin not found'], 404);
            }
            return response()->json($admin);
        } else {
            $employee = Employee::where('user_id', $user->id)->first();
            $employee = Employee::with(['user', 'department', 'position'])->find($employee->id);
            if (!$employee) {
                return response()->json(['message' => 'Employee not found'], 404);
            }
            return response()->json($employee);
        }
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        $newToken = JWTAuth::refresh(JWTAuth::getToken());
        return response()->json(['token' => $newToken]);
    }
}
