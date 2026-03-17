<?php

namespace App\Traits;

use App\Services\PHPMailerService;
use Illuminate\Support\Facades\View;

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
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Welcome email failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send account approval email
     */
    protected function sendApprovalEmail($user)
    {
        try {
            $mailService = new PHPMailerService();
            
            $htmlContent = View::make('emails.account-approved', [
                'name' => $user->full_name ?? $user->name,
                'username' => $user->username,
                'designation' => $user->designation,
                'windowNum' => $user->window_num
            ])->render();
            
            $mailService->sendHTML(
                $user->email,
                'Your Account Has Been Approved',
                $htmlContent
            );
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Approval email failed: ' . $e->getMessage());
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
            
            return $mailService->sendHTML($to, $subject, $htmlContent);
        } catch (\Exception $e) {
            \Log::error('Custom email failed: ' . $e->getMessage());
            return false;
        }
    }
}