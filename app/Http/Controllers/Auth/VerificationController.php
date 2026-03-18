<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PHPMailerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    /**
     * Send OTP to email for verification
     */
    public function sendOtp(Request $request)
    {
        // Log the request for debugging
        Log::info('sendOtp called', ['email' => $request->email]);

        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            Log::warning('sendOtp validation failed', ['errors' => $validator->errors()->toArray()]);
            
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Generate 6-digit OTP
            $otp = sprintf("%06d", mt_rand(1, 999999));
            
            // Store OTP in cache with email as key (expires in 10 minutes)
            Cache::put('otp_' . $request->email, $otp, now()->addMinutes(10));
            
            Log::info('OTP generated', ['email' => $request->email, 'otp' => $otp]);
            
            // Send OTP via email
            $mailService = new PHPMailerService();
            
            // Create email content
            $htmlContent = view('emails.otp-verification', [
                'otp' => $otp,
                'email' => $request->email
            ])->render();
            
            // Send the email
            $result = $mailService->sendHTML(
                $request->email,
                'Email Verification OTP - National ID System',
                $htmlContent
            );
            
            Log::info('OTP email sent successfully', ['email' => $request->email]);
            
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your email'
            ]);
            
        } catch (\Exception $e) {
            Log::error('OTP sending failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        Log::info('verifyOtp called', ['email' => $request->email, 'otp' => $request->otp]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Get stored OTP from cache
            $storedOtp = Cache::get('otp_' . $request->email);

            if (!$storedOtp) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired. Please request a new one.'
                ]);
            }

            if ($storedOtp !== $request->otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP. Please try again.'
                ]);
            }

            // Mark email as verified in session
            session(['verified_email_' . $request->email => true]);

            // Clear OTP from cache
            Cache::forget('otp_' . $request->email);

            Log::info('Email verified successfully', ['email' => $request->email]);

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('OTP verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Verification failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Check if email is verified
     */
    public function checkVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'verified' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $isVerified = session('verified_email_' . $request->email, false);

        return response()->json([
            'verified' => $isVerified
        ]);
    }
}