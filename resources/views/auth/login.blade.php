{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - National ID Appointment System</title>

    <!-- Favicon / Logo -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/loading.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/loading.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- For better SEO and social sharing -->
    <meta property="og:title" content="National ID Appointment System">
    <meta property="og:description" content="Philippine National ID Appointment and Queue Management System">
    <meta property="og:image" content="{{ asset('images/loading.png') }}">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="overlay"></div>

    <div class="container sign-in-state" id="container">
        <!-- Forms Panel -->
        <div class="panel forms-panel">
            <div class="panel-header">
                <h2 id="formTitle">LOG IN</h2>
                <p id="formSubtitle">Welcome back! Please enter your details</p>
            </div>

            <!-- Alert Container for messages -->
            <div id="alertContainer">
                @if ($errors->has('login'))
                    <div class="alert alert-error">
                        <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ $errors->first('login') }}</span>
                    </div>
                @endif

                @if ($errors->any() && !$errors->has('login'))
                    <div class="alert alert-error">
                        <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Log In Form -->
            <div id="signinForm" class="form-section">
                <form method="POST" action="{{ route('login.submit') }}" autocomplete="off" id="loginForm">
                    @csrf
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username"
                            class="form-input @error('username') is-invalid @enderror" placeholder="Enter your username"
                            value="{{ old('username') }}" required autocomplete="off" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-input @error('password') is-invalid @enderror" placeholder="••••••••" required
                            autocomplete="off">
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" class="checkbox">
                            <span>Remember me</span>
                        </label>
                        <button type="button" class="forgot-link" onclick="showForgotPassword()">Forgot
                            password?</button>
                    </div>

                    <button type="submit" class="btn-primary" id="signinSubmit">LOG IN</button>
                </form>
            </div>

            <!-- Register Form -->
            <div id="signupForm" class="form-section hidden">
                <form method="POST" action="#" autocomplete="off" id="signupFormElement">
                    @csrf
                    <div class="form-group">
                        <label for="signup-name">Full Name</label>
                        <input type="text" id="signup-name" name="full_name" class="form-input"
                            placeholder="John Doe" required>
                    </div>

                    <div class="form-group">
                        <label for="signup-username">Username</label>
                        <input type="text" id="signup-username" name="username" class="form-input"
                            placeholder="johndoe" required>
                    </div>

                    <div class="form-group">
                        <label for="signup-password">Password</label>
                        <input type="password" id="signup-password" name="password" class="form-input"
                            placeholder="••••••••" required>
                    </div>

                    <div class="form-group">
                        <label for="signup-password_confirmation">Confirm Password</label>
                        <input type="password" id="signup-password_confirmation" name="password_confirmation"
                            class="form-input" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn-primary">Create Account</button>
                </form>
            </div>

            <!-- Forgot Password Form -->
            <div id="forgotForm" class="form-section hidden">
                <form method="POST" action="#" autocomplete="off" id="forgotFormElement">

                    <div class="form-group">
                        <label for="forgot-username">Username</label>
                        <input type="text" id="forgot-username" name="username" class="form-input"
                            placeholder="Enter your username" required>
                    </div>
                    <button type="submit" class="btn-primary">Send Reset Link</button>
                    <div class="forgot-password-text">
                    </div>
                    <br>
                    <div>
                        <button type="button" class="forgot-link" onclick="showSignIn()">Back to Log In</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Welcome Panel with Logo -->
        <div class="panel welcome-panel" id="welcomePanel">
            <div class="welcome-logo">
                <img src="{{ asset('images/logo.png') }}" alt="National ID System" id="mainLogo">
            </div>
            <h2 id="welcomeTitle">Hello!</h2>
            <p id="welcomeMessage">Register with your personal details to use all of site features</p>
            <button class="btn-outline" id="toggleButton" onclick="toggleForm()">REGISTER</button>
        </div>
    </div>

    <!-- Loading Modal - Using loading.png as rotating logo -->
    <div class="loading-modal" id="loadingModal">
        <div class="loading-content">
            <img src="{{ asset('images/loading.png') }}" alt="Loading..." class="loading-logo rotate-logo">
            <p class="loading-text">Please wait...</p>
        </div>
    </div>

    <!-- Include jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>
