<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - National ID System</title>
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
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 40px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .otp-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin: 30px 0;
            border: 3px solid #0038A8;
            box-shadow: 0 10px 25px -5px rgba(0,56,168,0.2);
        }
        .otp-code {
            font-size: 48px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #0038A8;
            font-family: monospace;
        }
        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        .expiry {
            color: #ef4444;
            font-weight: 600;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>National ID System</h1>
        <h2>Email Verification</h2>
    </div>
    
    <div class="content">
        <p>Hello,</p>
        <p>Thank you for registering with the National ID System. Please use the following OTP to verify your email address:</p>
        
        <div class="otp-box">
            <div class="otp-code">{{ $otp }}</div>
        </div>
        
        <div class="warning">
            <strong>⚠️ Important:</strong>
            <ul style="margin-top: 5px; margin-bottom: 0;">
                <li>This OTP will expire in <strong>10 minutes</strong></li>
                <li>Never share this OTP with anyone</li>
                <li>If you didn't request this, please ignore this email</li>
            </ul>
        </div>
        
        <p class="expiry">OTP expires at: {{ now()->addMinutes(10)->format('F j, Y, g:i a') }}</p>
        
        <p>If you're having trouble, please contact support.</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} National ID System. All rights reserved.</p>
        <p>This is an automated message, please do not reply.</p>
    </div>
</body>
</html>