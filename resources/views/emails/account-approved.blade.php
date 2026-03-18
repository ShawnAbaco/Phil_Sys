<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Approved - National ID System</title>
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
        }
        .user-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #10b981;
        }
        .button {
            display: inline-block;
            padding: 14px 28px;
            background: #0038A8;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .button:hover {
            background: #002a7a;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px #0038A8;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            background: #10b981;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>National ID System</h1>
        <h2>Account Approved!</h2>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $name }}</strong>,</p>
        
        <p>We are pleased to inform you that your account has been <span class="status-badge">APPROVED</span> by the administrator.</p>
        
        <div class="user-details">
            <h3 style="margin-top: 0;">Your Account Details:</h3>
            <p><strong>Username:</strong> {{ $username }}</p>
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Designation:</strong> {{ $designation ?? 'Not assigned' }}</p>
            <p><strong>Window Number:</strong> {{ $window_num ?? 'Not assigned' }}</p>
        </div>
        
        <p>You can now log in to the National ID System using your credentials.</p>
        
        <div style="text-align: center;">
            <a href="{{ route('login') }}" class="button">LOGIN TO YOUR ACCOUNT</a>
        </div>
        
        <p>If you have any questions, please contact the system administrator.</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} National ID System. All rights reserved.</p>
        <p>This is an automated message, please do not reply.</p>
    </div>
</body>
</html>