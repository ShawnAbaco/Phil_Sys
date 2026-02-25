<?php
// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TblUser;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Get the username and password from the request
        $username = $request->input('username');
        $password = $request->input('password');

        // Find user with matching username and password (plain text as in original)
        $user = TblUser::where('username', $username)
                       ->where('password', $password)
                       ->first();

        if ($user) {
            // Store user data in session
            Session::put('user_id', $user->user_id);
            Session::put('full_name', $user->full_name);
            Session::put('designation', $user->designation);
            Session::put('window_num', $user->window_num);

            // Redirect based on designation
            $designation = strtolower(trim($user->designation));

            if ($designation === 'screener') {
                return redirect()->route('appointment.issuance');
            } else if ($designation === 'registration kit operator' || $designation === 'registration assistant') {
                return redirect()->route('operator.dashboard');
            } else if ($designation === 'client') {
                return redirect()->route('client.dashboard');
            } else {
                return redirect()->route('default.page');
            }
        } else {
            // Return back with error message
            return back()->withErrors([
                'login' => 'Invalid username or password',
            ])->withInput($request->except('password'));
        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect()->route('login');
    }
}
