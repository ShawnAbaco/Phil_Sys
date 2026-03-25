<?php

namespace App\Traits;

use App\Services\PHPMailerService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log; // Add this line


trait MailHelper
{
    /**
     * Send welcome email to new user
     */
    protected function sendWelcomeEmail($user)
    {
        try {
            $mailService = new PHPMailerService();
            
            $htmlContent = View::make('emails.welcome', [
                'name' => $user->full_name ?? $user->name,
                'username' => $user->username,
                'email' => $user->email
            ])->render();
            
            $mailService->sendHTML(
                $user->email,
                'Welcome to National ID System',
                $htmlContent
            );
            
            Log::info('Welcome email sent', ['user_id' => $user->id, 'email' => $user->email]);
            return true;
        } catch (\Exception $e) {
            Log::error('Welcome email failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send account approval email to user
     */
    protected function sendApprovalEmail($user)
    {
        try {
            $mailService = new PHPMailerService();
            
            $htmlContent = View::make('emails.account-approved', [
                'name' => $user->full_name ?? $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'designation' => $user->designation,
                'window_num' => $user->window_num
            ])->render();
            
            $mailService->sendHTML(
                $user->email,
                'Your Account Has Been Approved - National ID System',
                $htmlContent
            );
            
            Log::info('Approval email sent', ['user_id' => $user->id, 'email' => $user->email]);
            return true;
        } catch (\Exception $e) {
            Log::error('Approval email failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send custom email
     */
    protected function sendCustomEmail($to, $subject, $view, $data = [])
    {
        try {
            $mailService = new PHPMailerService();
            
            $htmlContent = View::make($view, $data)->render();
            
            $result = $mailService->sendHTML($to, $subject, $htmlContent);
            
            Log::info('Custom email sent', ['to' => $to, 'subject' => $subject]);
            return $result;
        } catch (\Exception $e) {
            Log::error('Custom email failed: ' . $e->getMessage(), [
                'to' => $to,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}