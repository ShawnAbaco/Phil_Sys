<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // If already logged in, redirect to appropriate dashboard
        if (Auth::check()) {
            return $this->redirectAuthenticatedUser(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by username from users table (not tbl_user)
        $user = User::where('username', $request->username)->first();

        // Check if user exists and password is correct (using Hash::check)
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid username or password')->withInput();
        }

        // Log the user in using Laravel's Auth system
        Auth::login($user, $request->boolean('remember'));

        // Store in session for your custom middleware
        Session::put('user_id', $user->id);
        Session::put('full_name', $user->full_name);
        Session::put('designation', $user->designation);
        Session::put('window_num', $user->window_num);

        // Regenerate session for security
        $request->session()->regenerate();

        // Redirect based on designation
        return $this->redirectAuthenticatedUser($user);
    }

    /**
     * Redirect authenticated user based on designation
     */
    protected function redirectAuthenticatedUser($user)
    {
        $designation = strtolower(trim($user->designation ?? ''));

        if ($designation == 'screener') {
            return redirect()->route('appointment.issuance');
        }
        elseif (in_array($designation, ['registration kit operator', 'registration assistant', 'operator'])) {
            return redirect()->route('operator.dashboard');
        }
        elseif (in_array($designation, ['administrator', 'super administrator', 'admin', 'superadmin'])) {
            return redirect()->route('admin.dashboard');
        }
        elseif ($designation == 'operations manager' || $designation == 'team supervisor') {
            return redirect()->route('admin.dashboard');
        }
        elseif ($designation == 'technical support') {
            return redirect()->route('admin.dashboard');
        }

        // Default redirect
        return redirect('/client-dashboard');
    }

    public function logout(Request $request)
    {
        // Logout using Laravel's Auth
        Auth::logout();

        // Clear all session data
        Session::flush();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
