{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - National ID Appointment System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: white;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            text-align: center;
            width: 320px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        h1 {
            margin: 0 0 20px 0;
            font-size: 24px;
            color: #007BFF;
        }
        .login-box {
            background: #f0f4f8;
            padding: 25px 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 14px;
        }
        input[type=submit] {
            background: #007BFF;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        input[type=submit]:hover {
            background: #0056b3;
        }
        .error {
            color: #d9534f;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('logo.png') }}" alt="National ID Logo" class="logo" />
        <h1>National ID Appointment System</h1>
        <div class="login-box">

            {{-- Display login error --}}
            @if($errors->has('login'))
                <div class="alert alert-danger">
                    {{ $errors->first('login') }}
                </div>
            @endif

            {{-- Display any other validation errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px; text-align: left;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}" autocomplete="off">
                @csrf {{-- Laravel CSRF protection --}}

                <input type="text"
                       name="username"
                       placeholder="Username"
                       value="{{ old('username') }}"
                       required
                       autofocus />

                <input type="password"
                       name="password"
                       placeholder="Password"
                       required />

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
