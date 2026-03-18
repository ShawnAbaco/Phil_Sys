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
    // Check if email is verified
    if (!session('verified_email_' . $request->email, false)) {
        return redirect()->back()
            ->with('error', 'Please verify your email first before creating an account.')
            ->withInput();
    }
    
    // Validate input
    $validator = Validator::make($request->all(), [
        'full_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'username' => 'required|string|min:3|max:99|unique:users|regex:/^[a-zA-Z0-9_.]+$/',
        'designation' => 'required|string|max:99',
        'window_num' => 'required|integer|min:0|max:99',
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
        'designation.required' => 'Please select your designation.',
        'window_num.required' => 'Please select your window number.',
        'window_num.integer' => 'Window number must be a valid number.',
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

        // Hash the password
        $hashedPassword = Hash::make($request->password);

        // Create new user with email already verified (via OTP)
        $user = User::create([
            'name' => $request->full_name,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $hashedPassword, // Laravel's default password field
            'password_hashed' => $request->password, // Store PLAIN password (for backward compatibility)
            'designation' => $request->designation, // Store the selected designation
            'window_num' => $request->window_num, // Store the selected window number
            'email_verified_at' => now(), // Email verified via OTP at registration time
            'status' => 'pending', // Still pending admin approval
        ]);

        DB::commit();

        // Send welcome email to user
        $this->sendWelcomeEmail($user);
        
        // Send approval request to admin
        $this->sendAdminApprovalEmail($user);

        Log::info('New user registered', [
            'user_id' => $user->id, 
            'username' => $user->username,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at
        ]);

        return redirect()->route('login')
            ->with('success', 'Registration successful! The admin has been notified to approve your account. Please check your email for confirmation.');

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Registration failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
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
            'username' => 'required|string|min:3|max:99|regex:/^[a-zA-Z0-9_.]+$/',
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
    
    /**
     * Send approval request to admin
     */
    protected function sendAdminApprovalEmail($user)
    {
        try {
            $mailService = new \App\Services\PHPMailerService();
            
            // Create signed token
            $expires = now()->addDays(7)->timestamp;
            $payload = $user->id . '|' . $expires . '|' . $user->email;
            $signature = hash_hmac('sha256', $payload, config('app.key'));
            $token = base64_encode($payload . '|' . $signature);
            $token = str_replace(['+', '/', '='], ['-', '_', ''], $token);
            
            $approvalUrl = route('admin.approve.user', ['token' => $token]);
            $rejectUrl = route('admin.reject.user', ['token' => $token]);
            
            $htmlContent = view('emails.admin-approval', [
                'user' => $user,
                'approvalUrl' => $approvalUrl,
                'rejectUrl' => $rejectUrl
            ])->render();
            
            $adminEmail = env('ADMIN_EMAIL', 'shawnabaco8@gmail.com');
            
            $mailService->sendHTML(
                $adminEmail,
                'New User Registration Pending Approval',
                $htmlContent
            );
            
            return true;
        } catch (\Exception $e) {
            Log::error('Admin approval email failed: ' . $e->getMessage());
            return false;
        }
    }
}