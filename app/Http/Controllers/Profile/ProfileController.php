<?php
namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Schema; // <-- ADD THIS LINE


class ProfileController extends Controller
{
    public function settings()
    {
        $user = Auth::user();
        
        // Get the same session data that your header component uses
        $userRole = session('user_role');
        $designation = session('designation');
        $windowNum = session('window_num');
        $displayName = session('full_name');
        $username = $user->username ?? $user->email; // Fallback to email if no username
        
        // Define the same helper functions that your header component uses
        $isOperator = function() use ($userRole, $designation) {
            return $userRole === 'operator' ||
                   str_contains(strtolower($designation ?? ''), 'operator');
        };
        
        $isScreener = function() use ($userRole, $designation) {
            return $userRole === 'screener' ||
                   str_contains(strtolower($designation ?? ''), 'screener');
        };
        
        return view('profile.settings', [
            'title' => 'Profile Settings',
            'displayName' => $displayName ?? $user->name,
            'username' => $username,
            'userEmail' => $user->email,
            'windowNum' => $windowNum,
            'designation' => $designation,
            'isOperator' => $isOperator,
            'isScreener' => $isScreener,
            'userRole' => $userRole
        ]);
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];
        
        // Add username validation if your users table has a username column
        if (Schema::hasColumn('users', 'username')) {
            $rules['username'] = 'required|string|min:3|max:50|unique:users,username,' . $user->id . '|regex:/^[a-zA-Z0-9_]+$/';
        }
        
        $request->validate($rules, [
            'username.regex' => 'Username may only contain letters, numbers, and underscores.',
            'username.unique' => 'This username is already taken.',
            'username.min' => 'Username must be at least 3 characters.',
        ]);
        
        $user->name = $request->name;
        $user->full_name = $request->name; // Update both name fields if needed
        $user->email = $request->email;
        
        // Update username if the column exists
        if (Schema::hasColumn('users', 'username')) {
            $user->username = $request->username;
        }
        
        $user->save();
        
        // Update session data
        session(['full_name' => $request->name]);
        
        return back()->with('success', 'Profile updated successfully!');
    }
    
    public function password(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&.,_])[A-Za-z\d@$!%*#?&.,_]{8,}$/',
            ],
        ], [
            'new_password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*#?&.,_).',
            'new_password.min' => 'The password must be at least 8 characters long.',
            'new_password.confirmed' => 'The password confirmation does not match.',
        ]);
        
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        return back()->with('success', 'Password changed successfully!');
    }
}