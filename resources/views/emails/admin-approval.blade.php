<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User Registration - National ID System</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #0038A8, #CE1126);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #ddd;
        }
        .user-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #0038A8;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .button {
            display: inline-block;
            padding: 14px 28px;
            background: #10b981;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 10px 5px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .button.reject {
            background: #ef4444;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
        .expiry {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            margin: 20px 0;
            font-size: 14px;
            border-radius: 4px;
        }
        .system-url {
            background: #e0f2fe;
            border-left: 4px solid #0284c7;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>National ID System</h1>
        <h2>New User Registration</h2>
    </div>
    
    <div class="content">
        <div class="system-url">
            <strong>🔗 System URL:</strong> {{ config('app.url') }}
        </div>
        
        <p>A new user has registered and is waiting for your approval.</p>
        
        <div class="user-details">
            <h3 style="margin-top: 0;">User Details:</h3>
            <p><strong>Name:</strong> {{ $user->full_name }}</p>
            <p><strong>Username:</strong> {{ $user->username }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Registered:</strong> {{ $user->created_at->format('F j, Y, g:i a') }}</p>
            <p><strong>Status:</strong> <span style="color: #f59e0b; font-weight: bold;">Pending Approval</span></p>
        </div>
        
        <p>Please click one of the buttons below to process this request:</p>
        
        <div style="text-align: center;">
            <a href="{{ $approvalUrl }}" class="button">✓ APPROVE USER</a>
            <a href="{{ $rejectUrl }}" class="button reject">✗ REJECT USER</a>
        </div>
        
        <div class="expiry">
            ⚠️ <strong>This approval link will expire in 7 days</strong><br>
            <small>Expires on: {{ now()->addDays(7)->format('F j, Y, g:i a') }}</small>
        </div>
        
        <p style="font-size: 14px; color: #666;">If you're unable to click the buttons, copy and paste these URLs into your browser:</p>
        <p style="word-break: break-all; font-size: 12px; background: #f3f4f6; padding: 10px; border-radius: 4px;">
            <strong>Approve:</strong> {{ $approvalUrl }}
        </p>
        <p style="word-break: break-all; font-size: 12px; background: #f3f4f6; padding: 10px; border-radius: 4px;">
            <strong>Reject:</strong> {{ $rejectUrl }}
        </p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} National ID System. All rights reserved.</p>
        <p>This is an automated message from the system. Please do not reply.</p>
    </div>
</body>
</html>