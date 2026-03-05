<?php
namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function settings()
    {
        $user = Auth::user();
        
        // Get the same session data that your header component uses
        $userRole = session('user_role');
        $designation = session('designation');
        $windowNum = session('window_num');
        $userName = session('full_name');
        
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
            'userName' => $userName ?? $user->name,
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);
        
        $user = Auth::user();
        $user->name = $request->name;
        $user->full_name = $request->name; // Update both name fields if needed
        $user->email = $request->email;
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
            // More flexible regex that allows more special characters
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