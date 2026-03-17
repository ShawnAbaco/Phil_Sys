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
                
                @if(session('success'))
                    <div class="alert alert-success">
                        <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('success') }}</span>
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
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password"
                                class="form-input @error('password') is-invalid @enderror" placeholder="••••••••"
                                required autocomplete="off">
                            <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                                <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
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
                <form method="POST" action="{{ route('register.submit') }}" autocomplete="off" id="signupFormElement">
                    @csrf
                    <div class="form-group">
                        <label for="signup-fullname">Full Name</label>
                        <input type="text" id="signup-fullname" name="full_name" class="form-input"
                            placeholder="John Doe" value="{{ old('full_name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="signup-email">Email Address</label>
                        <input type="email" id="signup-email" name="email" class="form-input"
                            placeholder="john@example.com" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="signup-username">Username</label>
                        <input type="text" id="signup-username" name="username" class="form-input"
                            placeholder="johndoe" value="{{ old('username') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="signup-password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="signup-password" name="password" class="form-input"
                                placeholder="••••••••" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('signup-password', this)">
                                <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="signup-password_confirmation">Confirm Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="signup-password_confirmation" name="password_confirmation"
                                class="form-input" placeholder="••••••••" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('signup-password_confirmation', this)">
                                <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="password-requirements">
                        <p>Password must contain:</p>
                        <ul>
                            <li id="length-check" class="requirement">At least 8 characters</li>
                            <li id="uppercase-check" class="requirement">At least one uppercase letter</li>
                            <li id="lowercase-check" class="requirement">At least one lowercase letter</li>
                            <li id="number-check" class="requirement">At least one number</li>
                            <li id="special-check" class="requirement">At least one special character</li>
                        </ul>
                    </div>

                    <button type="submit" class="btn-primary" id="signupSubmit">CREATE ACCOUNT</button>
                </form>
            </div>

            <!-- Forgot Password Form -->
            <div id="forgotForm" class="form-section hidden">
                <form method="POST" action="{{ route('password.email') }}" autocomplete="off" id="forgotFormElement">
                    @csrf
                    <div class="form-group">
                        <label for="forgot-email">Email Address</label>
                        <input type="email" id="forgot-email" name="email" class="form-input"
                            placeholder="Enter your email" required>
                    </div>
                    <button type="submit" class="btn-primary">Send Reset Link</button>
                    <div class="forgot-password-text"></div>
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
    
    <script>
        // Toggle password visibility
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            // Toggle the eye icon if needed (optional)
            const svg = button.querySelector('svg');
            if (type === 'text') {
                svg.style.opacity = '0.7';
            } else {
                svg.style.opacity = '1';
            }
        }
        
        // Password validation for registration
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('signup-password');
            
            if (passwordInput) {
                const requirements = {
                    length: document.getElementById('length-check'),
                    uppercase: document.getElementById('uppercase-check'),
                    lowercase: document.getElementById('lowercase-check'),
                    number: document.getElementById('number-check'),
                    special: document.getElementById('special-check')
                };
                
                function validatePassword() {
                    const password = passwordInput.value;
                    
                    // Length check
                    if (requirements.length) {
                        requirements.length.classList.toggle('valid', password.length >= 8);
                    }
                    
                    // Uppercase check
                    if (requirements.uppercase) {
                        requirements.uppercase.classList.toggle('valid', /[A-Z]/.test(password));
                    }
                    
                    // Lowercase check
                    if (requirements.lowercase) {
                        requirements.lowercase.classList.toggle('valid', /[a-z]/.test(password));
                    }
                    
                    // Number check
                    if (requirements.number) {
                        requirements.number.classList.toggle('valid', /[0-9]/.test(password));
                    }
                    
                    // Special character check
                    if (requirements.special) {
                        requirements.special.classList.toggle('valid', /[!@#$%^&*(),.?":{}|<>]/.test(password));
                    }
                }
                
                passwordInput.addEventListener('input', validatePassword);
            }
            
            // Show loading modal on form submit
            const signupForm = document.getElementById('signupFormElement');
            const loginForm = document.getElementById('loginForm');
            
            if (signupForm) {
                signupForm.addEventListener('submit', function() {
                    document.getElementById('loadingModal').classList.add('show');
                });
            }
            
            if (loginForm) {
                loginForm.addEventListener('submit', function() {
                    document.getElementById('loadingModal').classList.add('show');
                });
            }
        });
    </script>


<script>
    // Check username availability
    function checkUsername(username) {
        $.ajax({
            url: '{{ route("check.username") }}',
            type: 'POST',
            data: { username: username },
            success: function(response) {
                if (!response.available) {
                    showAlert('error', response.message);
                }
            },
            error: function(xhr) {
                console.error('Username check failed:', xhr);
            }
        });
    }

    // Check email availability
    function checkEmail(email) {
        $.ajax({
            url: '{{ route("check.email") }}',
            type: 'POST',
            data: { email: email },
            success: function(response) {
                if (!response.available) {
                    showAlert('error', response.message);
                }
            },
            error: function(xhr) {
                console.error('Email check failed:', xhr);
            }
        });
    }
</script>


</body>

</html>