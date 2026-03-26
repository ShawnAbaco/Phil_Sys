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

    // Find user by username
    $user = User::where('username', $request->username)->first();

    // Check if user exists
    if (!$user) {
        return back()->withErrors(['login' => 'Username not found'])->withInput();
    }

    // Check if password is correct
    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['login' => 'Incorrect password'])->withInput();
    }

    // Check if account is active
    if ($user->status !== 'active') {
        if ($user->status === 'pending') {
            return back()->withErrors(['login' => 'Your account is pending admin approval. Please wait for activation.'])->withInput();
        } elseif ($user->status === 'rejected') {
            return back()->withErrors(['login' => 'Your registration has been rejected. Please contact administrator.'])->withInput();
        } else {
            return back()->withErrors(['login' => 'Your account is not active. Please contact administrator.'])->withInput();
        }
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

        // Screener
        if ($designation == 'screener') {
            return redirect()->route('screener.dashboard');
        }
        // Registration Kit Operator
        elseif ($designation == 'registration kit operator') {
            return redirect()->route('operator.dashboard');
        }
        // Registration Assistant
        elseif ($designation == 'registration assistant') {
            return redirect()->route('operator.dashboard');
        }
        // Operator (generic)
        elseif ($designation == 'operator') {
            return redirect()->route('operator.dashboard');
        }
        // Admin roles
        elseif (in_array($designation, ['administrator', 'super administrator', 'admin', 'superadmin'])) {
            return redirect()->route('admin.dashboard');
        }
        elseif ($designation == 'operations manager' || $designation == 'team supervisor') {
            return redirect()->route('admin.dashboard');
        }
        elseif ($designation == 'technical support') {
            return redirect()->route('admin.dashboard');
        }

        // Default redirect if designation doesn't match any of the above
        return redirect()->route('login')->with('error', 'Your account does not have a valid designation. Please contact administrator.');
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