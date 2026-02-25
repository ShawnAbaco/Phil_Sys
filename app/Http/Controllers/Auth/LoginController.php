<?php
// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TblUser;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $user = TblUser::where('username', $request->username)
                       ->where('password', $request->password)
                       ->first();

        if (!$user) {
            return back()->with('error', 'Invalid username or password');
        }

        // Login using Auth
        Auth::login($user);

        // Redirect based on designation
        $designation = strtolower(trim($user->designation));

        if ($designation == 'screener') {
            return redirect('/appointment-issuance');
        } elseif ($designation == 'registration kit operator' || $designation == 'registration assistant') {
            return redirect('/operator-dashboard');
        } elseif ($designation == 'client') {
            return redirect('/client-dashboard');
        } else {
            return redirect('/dashboard');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
