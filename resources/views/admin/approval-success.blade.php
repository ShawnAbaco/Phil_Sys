<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success - National ID System</title>
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
            border: 3px solid #10b981;
        }
        .icon {
            width: 100px;
            height: 100px;
            background: #10b981;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 48px;
            font-weight: bold;
            box-shadow: 0 10px 25px -5px #10b981;
        }
        h1 {
            color: #10b981;
            margin-bottom: 16px;
            font-size: 32px;
        }
        .details {
            background: #f9fafb;
            padding: 24px;
            border-radius: 16px;
            margin: 24px 0;
            text-align: left;
            border: 1px solid #e5e7eb;
        }
        .detail-item {
            display: flex;
            margin-bottom: 12px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }
        .detail-label {
            font-weight: 600;
            width: 100px;
            color: #4b5563;
        }
        .detail-value {
            color: #1f2937;
            flex: 1;
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
            border: none;
            cursor: pointer;
        }
        .button:hover {
            background: #002a7a;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px #0038A8;
        }
        .note {
            margin-top: 20px;
            font-size: 14px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">✓</div>
        <h1>Success!</h1>
        <p style="font-size: 18px; color: #4b5563;">{{ $message }}</p>
        
        <div class="details">
            <div class="detail-item">
                <span class="detail-label">Name:</span>
                <span class="detail-value">{{ $user->full_name }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Username:</span>
                <span class="detail-value">{{ $user->username }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ $user->email }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Status:</span>
                <span class="detail-value" style="color: #10b981; font-weight: 600;">APPROVED</span>
            </div>
        </div>
        
        <a href="{{ route('login') }}" class="button">Go to Login Page</a>
        
        <p class="note">The user has been notified via email about this approval.</p>
    </div>
</body>
</html>