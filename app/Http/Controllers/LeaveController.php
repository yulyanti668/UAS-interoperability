<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Leave;
use Tymon\JWTAuth\Facades\JWTAuth;

class LeaveController extends Controller{
    // Tirta Subagja - D112021114
    public function index()
    {
        
        $user = JWTAuth::parseToken()->authenticate();
        $employee = Employee::where('user_id', $user->id)->first();
        if ($user->role == "admin") {
            $leaves = Leave::with('employee')->get();
            return response()->json($leaves);
        } else {
            $leaves = Leave::with('employee')->where('employee_id', $employee->id)->get();
            return response()->json($leaves);
        }
    }

    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $employee = Employee::where('user_id', $user->id)->first();
        if ($user->role == "karyawan") {
            $this->validate($request, [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'required|string',
            ]);
    
            $leave = Leave::create([
                'employee_id' => $employee->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'status' => Leave::STATUS_PENDING,
            ]);
    
            return response()->json($leave, 201);
        } else {
            return response()->json(['message' => 'Leave request is forbidden for admin'], 403);
        }
    }

    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->role == "admin") {
            $leave = Leave::with('employee')->find($id);
            if (!$leave) {
                return response()->json(['message' => 'Leave request not found'], 404);
            }
            return response()->json($leave);
        } else {
            return response()->json(['message' => 'Leave request is forbidden for employee'], 403);
        }
    }

    public function approve($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user->role == "admin") {
            $leave = Leave::find($id);
            if (!$leave) {
                return response()->json(['message' => 'Leave request not found'], 404);
            }

            if ($leave->status !== Leave::STATUS_PENDING) {
                return response()->json(['message' => 'Leave request is already processed'], 400);
            }

            $leave->update(['status' => Leave::STATUS_APPROVED]);

            return response()->json(['message' => 'Leave request approved', 'leave' => $leave]);
        } else {
            return response()->json(['message' => 'Leave request is forbidden for employee'], 403);
        }
        
        
    }

    public function reject($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user->role == "admin") {
            $leave = Leave::find($id);
            if (!$leave) {
                return response()->json(['message' => 'Leave request not found'], 404);
            }

            if ($leave->status !== Leave::STATUS_PENDING) {
                return response()->json(['message' => 'Leave request is already processed'], 400);
            }

            $leave->update(['status' => Leave::STATUS_REJECTED]);

            return response()->json(['message' => 'Leave request rejected', 'leave' => $leave]);
        } else {
            return response()->json(['message' => 'Leave request is forbidden for employee'], 403);
        }
    }
}