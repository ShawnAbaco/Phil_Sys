<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to National ID System</title>
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
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #0038A8;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .status-badge {
            background: #f59e0b;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>National ID System</h1>
    </div>
    <div class="content">
        <h2>Welcome, {{ $name }}!</h2>
        <p>Thank you for registering with the National ID System. Your account has been created successfully.</p>
        
        <p><strong>Account Details:</strong></p>
        <ul>
            <li>Username: {{ $username }}</li>
            <li>Email: {{ $email }}</li>
            <li>Status: <span class="status-badge">Pending Approval</span></li>
        </ul>
        
        <p>Your account is currently pending approval from an administrator. You will receive another email once your account has been activated.</p>
        
        <p>If you have any questions, please contact the system administrator.</p>
        
        <center>
            <a href="{{ route('login') }}" class="button">Go to Login</a>
        </center>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} National ID System. All rights reserved.</p>
        <p>This is an automated message, please do not reply.</p>
    </div>
</body>
</html>