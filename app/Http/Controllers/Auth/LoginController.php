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
        // Simple check
        $user = TblUser::where('username', $request->username)
                       ->where('password', $request->password)
                       ->first();

        if (!$user) {
            return back()->with('error', 'Invalid username or password');
        }

        // Store in session
        Session::put('user_id', $user->user_id);
        Session::put('full_name', $user->full_name);
        Session::put('designation', $user->designation);
        Session::put('window_num', $user->window_num);

        // Redirect based on designation
        $designation = strtolower(trim($user->designation));

        if ($designation == 'screener') {
            return redirect('/appointment-issuance');
        } elseif ($designation == 'registration kit operator' || $designation == 'registration assistant') {
            return redirect()->route('operator.dashboard');
        } elseif ($designation == 'client') {
            return redirect('/client-dashboard');
        } else {
            return redirect('/dashboard');
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}
