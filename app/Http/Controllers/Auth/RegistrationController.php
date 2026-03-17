<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\MailHelper;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
     use MailHelper; // Add this trait

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|min:3|max:99|unique:users|regex:/^[a-zA-Z0-9_.]+$/',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&.,_])[A-Za-z\d@$!%*#?&.,_]{8,}$/',
            ],
        ], [
            'username.regex' => 'Username may only contain letters, numbers, underscores, and dots.',
            'username.unique' => 'This username is already taken.',
            'username.min' => 'Username must be at least 3 characters.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*#?&.,_).',
            'password.min' => 'The password must be at least 8 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'email.unique' => 'This email is already registered.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create new user
            $user = User::create([
                'name' => $request->full_name,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'designation' => 'pending',
                'window_num' => 0,
            ]);

            DB::commit();

            // Log the registration
            Log::info('New user registered', ['user_id' => $user->id, 'username' => $user->username]);

            // Send welcome email
            $this->sendWelcomeEmail($user);

            // Redirect to login with success message
            return redirect()->route('login')
                ->with('success', 'Registration successful! Please check your email for confirmation. Your account is pending admin approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Registration failed', ['error' => $e->getMessage()]);
            
            return redirect()->back()
                ->with('error', 'Registration failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Check if username is available (AJAX)
     */
    public function checkUsername(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:99|regex:/^[a-zA-Z0-9_.]+$/', // Added dot to regex
        ]);

        if ($validator->fails()) {
            return response()->json([
                'available' => false,
                'message' => 'Username may only contain letters, numbers, underscores, and dots.'
            ]);
        }

        $exists = User::where('username', $request->username)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Username already taken' : 'Username available'
        ]);
    }

    /**
     * Check if email is available (AJAX)
     */
    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'available' => false,
                'message' => 'Invalid email format'
            ]);
        }

        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Email already registered' : 'Email available'
        ]);
    }
}