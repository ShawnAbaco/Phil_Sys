<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Approved - National ID System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>National ID System</h1>
    </div>
    <div class="content">
        <h2>Account Approved!</h2>
        <p>Dear {{ $name }},</p>
        
        <p>We are pleased to inform you that your account has been approved by the administrator. You can now log in to the National ID System.</p>
        
        <p><strong>Your Details:</strong></p>
        <ul>
            <li>Username: {{ $username }}</li>
            <li>Designation: {{ $designation ?? 'Not assigned' }}</li>
            <li>Window Number: {{ $windowNum ?? 'Not assigned' }}</li>
        </ul>
        
        <p>You can now access the system using your credentials.</p>
        
        <center>
            <a href="{{ route('login') }}" class="button">Login to System</a>
        </center>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} National ID System. All rights reserved.</p>
        <p>This is an automated message, please do not reply.</p>
    </div>
</body>
</html>