<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - National ID System</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 24px;
            padding: 48px;
            max-width: 500px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            text-align: center;
            border: 3px solid #ef4444;
        }
        .icon {
            width: 100px;
            height: 100px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 48px;
            font-weight: bold;
            box-shadow: 0 10px 25px -5px #ef4444;
        }
        h1 {
            color: #ef4444;
            margin-bottom: 16px;
            font-size: 32px;
        }
        .error-message {
            background: #fef2f2;
            padding: 20px;
            border-radius: 12px;
            margin: 24px 0;
            color: #991b1b;
            border: 1px solid #fecaca;
            font-size: 16px;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: #0038A8;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            margin: 5px;
        }
        .button:hover {
            background: #002a7a;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px #0038A8;
        }
        .button.secondary {
            background: #6b7280;
        }
        .button.secondary:hover {
            background: #4b5563;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">!</div>
        <h1>Error</h1>
        
        <div class="error-message">
            {{ $error }}
        </div>
        
        <p style="color: #6b7280; margin-bottom: 24px;">Possible reasons:</p>
        <ul style="text-align: left; color: #4b5563; margin-bottom: 24px;">
            <li>Link may have expired (links expire after 7 days)</li>
            <li>User may have already been approved</li>
            <li>Link may have been already used</li>
            <li>Invalid or corrupted link</li>
        </ul>
        
        <div>
            <a href="{{ route('login') }}" class="button">Go to Login</a>
            <a href="mailto:{{ env('ADMIN_EMAIL', 'admin@example.com') }}" class="button secondary">Contact Support</a>
        </div>
    </div>
</body>
</html>