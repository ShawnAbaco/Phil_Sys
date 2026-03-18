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

    {{-- Route URLs for AJAX calls --}}
    <meta name="check-email-url" content="{{ route('check.email') }}">
    <meta name="check-username-url" content="{{ route('check.username') }}">
    <meta name="send-otp-url" content="{{ route('verification.send-otp') }}">
    <meta name="verify-otp-url" content="{{ route('verification.verify-otp') }}">


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
    
    {{-- Add this section to handle 'error' session flashes --}}
    @if(session('error'))
        <div class="alert alert-error">
            <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>
            <span>{{ session('error') }}</span>
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
    <!-- Add this inside the register form, right after the opening form tag -->
    <div id="registerAlertContainer" style="margin-bottom: 15px;"></div>
    <form method="POST" action="{{ route('register.submit') }}" autocomplete="off" id="signupFormElement">
        @csrf
        <div class="form-group">
            <label for="signup-fullname">Full Name</label>
            <input type="text" id="signup-fullname" name="full_name" class="form-input"
                placeholder="John Doe" value="{{ old('full_name') }}" required>
        </div>

        <!-- Email Field with Verification -->
        <div class="form-group">
            <label for="signup-email">Email Address <span style="color: #CE1126;">*</span></label>
            <div style="display: flex; gap: 8px;">
                <input type="email" id="signup-email" name="email" class="form-input"
                    placeholder="john@example.com" value="{{ old('email') }}" required style="flex: 2;">
                <button type="button" id="sendOtpBtn" class="btn-otp" onclick="sendOtp()" style="flex: 1; white-space: nowrap;">Send OTP</button>
            </div>
            <small class="field-hint" style="color: #666; display: block; margin-top: 5px;">You must verify your email before creating an account</small>
        </div>

        <!-- OTP Verification Field -->
        <div id="otpSection" style="display: none; margin-bottom: 20px;">
            <div class="form-group">
                <label for="otp">Enter OTP</label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" id="otp" name="otp" class="form-input" 
                        placeholder="Enter 6-digit OTP" maxlength="6" style="flex: 2; text-align: center; font-size: 18px; letter-spacing: 4px;">
                    <button type="button" id="verifyOtpBtn" class="btn-verify" onclick="verifyOtp()" style="flex: 1;">Verify</button>
                </div>
                <div id="otpTimer" style="margin-top: 8px; font-size: 13px; color: #666;"></div>
                <div id="otpMessage" style="margin-top: 8px; font-size: 13px;"></div>
            </div>
        </div>

        <!-- Email Verified Indicator -->
        <div id="emailVerifiedBadge" style="display: none; margin-bottom: 20px;">
            <div style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; display: flex; align-items: center; gap: 8px;">
                <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>✅ Email verified successfully! You can now create your account.</span>
            </div>
        </div>

        <div class="form-group">
            <label for="signup-username">Username</label>
            <input type="text" id="signup-username" name="username" class="form-input"
                placeholder="johndoe" value="{{ old('username') }}" required>
        </div>

        <!-- Designation Field -->
        <div class="form-group">
            <label for="signup-designation">Designation</label>
            <select id="signup-designation" name="designation" class="form-input" required>
                <option value="" disabled selected>Select your designation</option>
                <option value="Registration Kit Operator" {{ old('designation') == 'Registration Kit Operator' ? 'selected' : '' }}>Registration Kit Operator</option>
                <option value="Registration Assistant" {{ old('designation') == 'Registration Assistant' ? 'selected' : '' }}>Registration Assistant</option>
                <option value="Screener" {{ old('designation') == 'Screener' ? 'selected' : '' }}>Screener</option>
                <!-- <option value="Administrator" {{ old('designation') == 'Administrator' ? 'selected' : '' }}>Administrator</option>
                <option value="Operations Manager" {{ old('designation') == 'Operations Manager' ? 'selected' : '' }}>Operations Manager</option>
                <option value="Team Supervisor" {{ old('designation') == 'Team Supervisor' ? 'selected' : '' }}>Team Supervisor</option>
                <option value="Technical Support" {{ old('designation') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option> -->
            </select>
            <small class="field-hint" style="color: #666; display: block; margin-top: 5px;">Select your role/designation</small>
        </div>

        <!-- Window Number Field -->
        <div class="form-group">
            <label for="signup-window_num">Window Number</label>
            <select id="signup-window_num" name="window_num" class="form-input" required>
                <option value="" disabled selected>Select your window number</option>
                <option value="1" {{ old('window_num') == '1' ? 'selected' : '' }}>Window 1</option>
                <option value="2" {{ old('window_num') == '2' ? 'selected' : '' }}>Window 2</option>
                <option value="3" {{ old('window_num') == '3' ? 'selected' : '' }}>Window 3</option>
                <option value="4" {{ old('window_num') == '4' ? 'selected' : '' }}>Window 4</option>
                <option value="5" {{ old('window_num') == '5' ? 'selected' : '' }}>Window 5</option>
                <option value="6" {{ old('window_num') == '6' ? 'selected' : '' }}>Window 6</option>
                <option value="0" {{ old('window_num') == '0' ? 'selected' : '' }}>No Window (Screener)</option>
            </select>
            <small class="field-hint" style="color: #666; display: block; margin-top: 5px;">Select your assigned window (0 for no window)</small>
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
    // Toggle password visibility (this is already in login.js, but kept for redundancy)
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        
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
                
                if (requirements.length) {
                    requirements.length.classList.toggle('valid', password.length >= 8);
                }
                if (requirements.uppercase) {
                    requirements.uppercase.classList.toggle('valid', /[A-Z]/.test(password));
                }
                if (requirements.lowercase) {
                    requirements.lowercase.classList.toggle('valid', /[a-z]/.test(password));
                }
                if (requirements.number) {
                    requirements.number.classList.toggle('valid', /[0-9]/.test(password));
                }
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

function validateAndFocus() {
    // Check Full Name
    const fullname = $('#signup-fullname').val();
    if (!fullname || fullname.trim() === '') {
        showAlert('error', 'Please enter your full name');
        $('#signup-fullname').focus();
        return false;
    }

    // Check Email
    const email = $('#signup-email').val();
    if (!email) {
        showAlert('error', 'Please enter your email address');
        $('#signup-email').focus();
        return false;
    }

    // Validate email format
    if (!isValidEmail(email)) {
        showAlert('error', 'Please enter a valid email address');
        $('#signup-email').focus();
        return false;
    }

    // Check if email is verified
    if (!$('#emailVerifiedBadge').is(':visible')) {
        // Check if OTP was sent but not verified
        if ($('#otpSection').is(':visible')) {
            showAlert('error', 'Please enter the OTP sent to your email and click Verify');
            $('#otp').focus();
        } else {
            showAlert('error', 'Please click "Send OTP" to verify your email first');
            $('#sendOtpBtn').focus();
        }
        return false;
    }

    // Check Username
    const username = $('#signup-username').val();
    if (!username) {
        showAlert('error', 'Please enter a username');
        $('#signup-username').focus();
        return false;
    }

    if (username.length < 3) {
        showAlert('error', 'Username must be at least 3 characters long');
        $('#signup-username').focus();
        return false;
    }

    // Check Designation
    const designation = $('#signup-designation').val();
    if (!designation) {
        showAlert('error', 'Please select your designation');
        $('#signup-designation').focus();
        return false;
    }

    // Check Window Number
    const windowNum = $('#signup-window_num').val();
    if (!windowNum) {
        showAlert('error', 'Please select your window number');
        $('#signup-window_num').focus();
        return false;
    }

    // Check Password
    const password = $('#signup-password').val();
    if (!password) {
        showAlert('error', 'Please enter a password');
        $('#signup-password').focus();
        return false;
    }

    // Check Password Requirements
    if (!isPasswordValid()) {
        let missingRequirements = [];
        if (password.length < 8) missingRequirements.push('at least 8 characters');
        if (!/[A-Z]/.test(password)) missingRequirements.push('one uppercase letter');
        if (!/[a-z]/.test(password)) missingRequirements.push('one lowercase letter');
        if (!/[0-9]/.test(password)) missingRequirements.push('one number');
        if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) missingRequirements.push('one special character');
        
        showAlert('error', 'Password must contain: ' + missingRequirements.join(', '));
        $('#signup-password').focus();
        return false;
    }

    // Check Password Confirmation
    const confirmPassword = $('#signup-password_confirmation').val();
    if (!confirmPassword) {
        showAlert('error', 'Please confirm your password');
        $('#signup-password_confirmation').focus();
        return false;
    }

    // Check if passwords match
    if (password !== confirmPassword) {
        showAlert('error', 'Passwords do not match');
        $('#signup-password_confirmation').focus();
        return false;
    }

    return true;
}

</script>


</body>

</html>
