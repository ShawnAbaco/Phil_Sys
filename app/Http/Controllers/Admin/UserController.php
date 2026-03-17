<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends AdminController
{


    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Apply filters
        if ($request->has('role') && !empty($request->role)) {
            $query->where('designation', $request->role);
        }
        
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        
        if ($request->ajax()) {
            return $this->sendSuccess('Users retrieved', [
                'users' => $users,
                'pagination' => $this->getPaginationData($users)
            ]);
        }
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'designation' => 'required|string|max:255',
            'window_num' => 'nullable|integer|between:1,12',
            'contact_number' => 'nullable|string|max:20',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female',
            'status' => 'nullable|string|in:active,inactive,suspended',
        ]);

        // Generate temporary password
        $tempPassword = Str::random(12);
        
        $user = new User();
        $user->name = $validated['full_name'];
        $user->full_name = $validated['full_name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->designation = $validated['designation'];
        $user->window_num = $validated['window_num'] ?? null;
        $user->contact_number = $validated['contact_number'] ?? null;
        $user->birthdate = $validated['birthdate'] ?? null;
        $user->gender = $validated['gender'] ?? null;
        $user->status = $validated['status'] ?? 'active';
        $user->password = Hash::make($tempPassword);
        $user->save();

        // TODO: Send email with temporary password

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully. Temporary password: ' . $tempPassword);
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        // Get user statistics
        $totalServed = TblAppointment::where('user_id', $id)->count();
        $todayServed = TblAppointment::where('user_id', $id)
            ->whereDate('time_catered', Carbon::today())
            ->count();
            
        $avgServiceTime = TblAppointment::where('user_id', $id)
            ->whereNotNull('time_catered')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, date, time_catered)) as avg_time')
            ->first();
            
        // Get recent activities
        $activities = TblAppointment::where('user_id', $id)
            ->orderBy('time_catered', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.users.show', compact('user', 'totalServed', 'todayServed', 'avgServiceTime', 'activities'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'designation' => 'required|string|max:255',
            'window_num' => 'nullable|integer|between:1,12',
            'contact_number' => 'nullable|string|max:20',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female',
            'status' => 'nullable|string|in:active,inactive,suspended',
        ]);

        $user->name = $validated['full_name'];
        $user->full_name = $validated['full_name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->designation = $validated['designation'];
        $user->window_num = $validated['window_num'] ?? null;
        $user->contact_number = $validated['contact_number'] ?? null;
        $user->birthdate = $validated['birthdate'] ?? null;
        $user->gender = $validated['gender'] ?? null;
        $user->status = $validated['status'] ?? 'active';
        $user->save();

        return redirect()->route('admin.users.show', $id)
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Check if user has any appointments
            $appointmentCount = TblAppointment::where('user_id', $id)->count();
            
            if ($appointmentCount > 0) {
                return $this->sendError('Cannot delete user with existing appointments');
            }
            
            $user->delete();
            
            return $this->sendSuccess('User deleted successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete user', $e->getMessage(), 500);
        }
    }

    /**
     * Reset user password.
     */
    public function resetPassword($id)
    {
        try {
            $user = User::findOrFail($id);
            $newPassword = Str::random(12);
            
            $user->password = Hash::make($newPassword);
            $user->save();
            
            // TODO: Send email with new password
            
            return $this->sendSuccess('Password reset successfully', [
                'new_password' => $newPassword
            ]);
            
        } catch (\Exception $e) {
            return $this->sendError('Failed to reset password', $e->getMessage(), 500);
        }
    }

    /**
     * Toggle user status.
     */
    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->status = $user->status === 'active' ? 'inactive' : 'active';
            $user->save();
            
            return $this->sendSuccess('User status updated', [
                'new_status' => $user->status
            ]);
            
        } catch (\Exception $e) {
            return $this->sendError('Failed to update user status', $e->getMessage(), 500);
        }
    }

    /**
     * Get users for dropdown (API).
     */
    public function getUsersList(Request $request)
    {
        $query = User::query();
        
        if ($request->has('role')) {
            $query->where('designation', 'like', '%' . $request->role . '%');
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->limit(20)->get(['id', 'name', 'username', 'designation', 'window_num']);
        
        return $this->sendSuccess('Users retrieved', $users);
    }
}