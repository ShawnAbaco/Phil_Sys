<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\MailHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApprovalController extends Controller
{
    use MailHelper;

    /**
     * Verify and decode the approval token
     */
    private function verifyToken($token)
    {
        try {
            // Restore URL-safe characters
            $token = str_replace(['-', '_'], ['+', '/'], $token);
            
            // Decode base64
            $decoded = base64_decode($token);
            if (!$decoded) {
                return null;
            }
            
            // Split payload and signature
            $parts = explode('|', $decoded);
            if (count($parts) !== 4) { // user_id|expires|email|signature
                return null;
            }
            
            $userId = $parts[0];
            $expires = $parts[1];
            $email = $parts[2];
            $signature = $parts[3];
            
            // Check if expired
            if (now()->timestamp > $expires) {
                return null;
            }
            
            // Verify signature
            $payload = $userId . '|' . $expires . '|' . $email;
            $expectedSignature = hash_hmac('sha256', $payload, config('app.key'));
            
            if (!hash_equals($expectedSignature, $signature)) {
                return null;
            }
            
            // Find user
            $user = User::find($userId);
            if (!$user || $user->email !== $email) {
                return null;
            }
            
            // Check if already approved
            if ($user->email_verified_at !== null) {
                return null;
            }
            
            return $user;
            
        } catch (\Exception $e) {
            Log::error('Token verification failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Approve user via token
     */
    public function approve($token)
{
    $user = $this->verifyToken($token);
    
    if (!$user) {
        return view('admin.approval-error', [
            'error' => 'Invalid, expired, or already processed approval link.'
        ]);
    }

    try {
        DB::beginTransaction();

        // User is already email verified (from registration)
        // Just update the status to active
        $user->update([
            'status' => 'active', // Mark as approved
        ]);

        DB::commit();

        // Send notification to user
        $this->sendApprovalEmail($user);

        Log::info('User approved via email', [
            'user_id' => $user->id,
            'email_verified_at' => $user->email_verified_at,
            'status' => 'active'
        ]);

        return view('admin.approval-success', [
            'user' => $user,
            'message' => 'User has been successfully approved! They can now log in.'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Approval failed', ['error' => $e->getMessage()]);
        
        return view('admin.approval-error', [
            'error' => 'An error occurred while processing the approval.'
        ]);
    }
}

    /**
     * Reject user via token
     */
    public function reject($token)
    {
        $user = $this->verifyToken($token);
        
        if (!$user) {
            return view('admin.approval-error', [
                'error' => 'Invalid, expired, or already processed link.'
            ]);
        }

        try {
            DB::beginTransaction();

            // Delete the user
            $user->delete();

            DB::commit();

            Log::info('User rejected via email', ['user_id' => $user->id]);

            return view('admin.approval-success', [
                'user' => $user,
                'message' => 'User registration has been rejected and removed from the system.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Rejection failed', ['error' => $e->getMessage()]);
            
            return view('admin.approval-error', [
                'error' => 'An error occurred while processing the rejection.'
            ]);
        }
    }
}