// =============================================
// Route URLs - These will be set from meta tags
// =============================================
let checkEmailUrl;
let checkUsernameUrl;
let sendOtpUrl;
let verifyOtpUrl;

// OTP Variables
let otpTimer;
let otpExpiryTime;

// =============================================
// Document Ready
// =============================================
$(document).ready(function() {
    // Set CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Initialize route URLs from meta tags
    checkEmailUrl = $('meta[name="check-email-url"]').attr('content');
    checkUsernameUrl = $('meta[name="check-username-url"]').attr('content');
    sendOtpUrl = $('meta[name="send-otp-url"]').attr('content');
    verifyOtpUrl = $('meta[name="verify-otp-url"]').attr('content');

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut(300);
    }, 5000);

    // Initialize form handlers
    initializeForms();

    // Set initial state
    setTimeout(() => {
        $('#container').addClass('sign-in-state');
    }, 100);
    
    // Reset OTP when email changes
    $('#signup-email').on('input', function() {
        if ($('#emailVerifiedBadge').is(':visible')) {
            $('#emailVerifiedBadge').slideUp();
        }
        $('#sendOtpBtn').prop('disabled', false).text('Send OTP').removeClass('sent');
        $('#otpSection').slideUp();
        $('#otp').val('');
        $('#otpTimer').html('');
        clearInterval(otpTimer);
    });
});

// =============================================
// Initialize Forms
// =============================================
function initializeForms() {
    // Login Form - Enhanced validation
    $('#loginForm').on('submit', function(e) {
        const username = $('#username').val();
        const password = $('#password').val();
        
        // Clear any existing alerts in the login container
        $('#alertContainer').empty();
        
        // Client-side validation
        if (!username || username.trim() === '') {
            e.preventDefault();
            showAlert('error', 'Please enter your username');
            $('#username').focus();
            return false;
        }
        
        if (!password) {
            e.preventDefault();
            showAlert('error', 'Please enter your password');
            $('#password').focus();
            return false;
        }
        
        // Show loading modal
        showLoading();
        return true; // Allow form submission
    });

    // Register Form - Validate and redirect to missing fields
    $('#signupFormElement').on('submit', function(e) {
        e.preventDefault();
        
        // Check all required fields and redirect to the first missing/invalid one
        if (!validateAndFocus()) {
            return false; // Don't show loading, don't submit
        }
        
        showLoading();
        this.submit(); // Manually submit the form
    });

    // Forgot Password Form - Keep AJAX
    $('#forgotFormElement').on('submit', function(e) {
        e.preventDefault();

        const email = $('#forgot-email').val();
        if (!email || !isValidEmail(email)) {
            showAlert('error', 'Please enter a valid email address');
            $('#forgot-email').focus();
            return;
        }

        $('#alertContainer').empty();
        showLoading();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                hideLoading();

                if (response.success) {
                    showAlert('success', response.message);

                    setTimeout(() => {
                        $('#forgotFormElement')[0].reset();
                        showSignIn();
                    }, 2000);
                } else {
                    showAlert('error', response.message || 'Request failed');
                }
            },
            error: function(xhr) {
                hideLoading();

                if (xhr.status === 422) {
                    showAlert('error', 'Email not found in our records.');
                } else if (xhr.status === 0) {
                    if (confirm('Connection error. Submit form traditionally?')) {
                        $('#forgotFormElement')[0].submit();
                    }
                } else {
                    showAlert('error', 'An error occurred. Please try again.');
                }
            }
        });
    });

    // Add real-time validation for username
    $('#signup-username').on('blur', function() {
        const username = $(this).val();
        if (username.length >= 3) {
            checkUsername(username);
        }
    });

    // Add real-time validation for email
    $('#signup-email').on('blur', function() {
        const email = $(this).val();
        if (email.includes('@') && email.includes('.')) {
            checkEmail(email);
        }
    });
}

// =============================================
// Validation and Focus Functions
// =============================================
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

function isPasswordValid() {
    const password = $('#signup-password').val();
    
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };

    return requirements.length && requirements.uppercase && 
           requirements.lowercase && requirements.number && requirements.special;
}

// =============================================
// OTP Functions
// =============================================

// Send OTP to email
function sendOtp() {
    const email = $('#signup-email').val();
    
    if (!email || !isValidEmail(email)) {
        showAlert('error', 'Please enter a valid email address');
        $('#signup-email').focus();
        return;
    }
    
    // Check if email already exists
    $('#sendOtpBtn').prop('disabled', true).text('Checking...');
    
    $.ajax({
        url: checkEmailUrl,
        type: 'POST',
        data: { email: email },
        success: function(response) {
            if (!response.available) {
                $('#sendOtpBtn').prop('disabled', false).text('Send OTP');
                showAlert('error', 'Email already registered');
                $('#signup-email').focus();
                return;
            }
            
            // Proceed to send OTP
            $('#sendOtpBtn').text('Sending OTP...');
            
            $.ajax({
                url: sendOtpUrl,
                type: 'POST',
                data: { email: email },
                success: function(response) {
                    if (response.success) {
                        $('#sendOtpBtn').text('OTP Sent ✓').addClass('sent');
                        $('#otpSection').slideDown();
                        $('#otp').focus();
                        
                        // Set OTP expiry (10 minutes from now)
                        otpExpiryTime = Date.now() + 600000; // 10 minutes
                        startOtpTimer();
                        
                        showAlert('success', 'OTP sent to your email! Check your inbox.');
                    } else {
                        $('#sendOtpBtn').prop('disabled', false).text('Send OTP');
                        showAlert('error', response.message);
                    }
                },
                error: function(xhr) {
                    $('#sendOtpBtn').prop('disabled', false).text('Send OTP');
                    let errorMsg = 'Failed to send OTP. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showAlert('error', errorMsg);
                }
            });
        },
        error: function(xhr) {
            $('#sendOtpBtn').prop('disabled', false).text('Send OTP');
            showAlert('error', 'Failed to check email availability');
        }
    });
}

// Verify OTP
function verifyOtp() {
    const email = $('#signup-email').val();
    const otp = $('#otp').val();
    
    if (!otp || otp.length !== 6) {
        showAlert('error', 'Please enter a valid 6-digit OTP');
        $('#otp').focus();
        return;
    }
    
    $('#verifyOtpBtn').prop('disabled', true).text('Verifying...');
    
    $.ajax({
        url: verifyOtpUrl,
        type: 'POST',
        data: { email: email, otp: otp },
        success: function(response) {
            if (response.success) {
                $('#otpSection').slideUp();
                $('#emailVerifiedBadge').slideDown();
                $('#sendOtpBtn').prop('disabled', true);
                $('#otp').val('');
                clearInterval(otpTimer);
                $('#otpTimer').html('');
                
                showAlert('success', '✅ Email verified successfully! You can now create your account.');
                
                // Auto-focus to username field after successful verification
                $('#signup-username').focus();
            } else {
                $('#verifyOtpBtn').prop('disabled', false).text('Verify');
                showAlert('error', response.message);
                $('#otp').focus();
            }
        },
        error: function(xhr) {
            $('#verifyOtpBtn').prop('disabled', false).text('Verify');
            let errorMsg = 'Failed to verify OTP. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            showAlert('error', errorMsg);
        }
    });
}

// Start OTP timer
function startOtpTimer() {
    clearInterval(otpTimer);
    
    otpTimer = setInterval(function() {
        const now = Date.now();
        const timeLeft = otpExpiryTime - now;
        
        if (timeLeft <= 0) {
            clearInterval(otpTimer);
            $('#otpTimer').html('OTP expired! Please request a new one.').addClass('expired');
            $('#sendOtpBtn').prop('disabled', false).text('Send OTP').removeClass('sent');
            $('#verifyOtpBtn').prop('disabled', true);
            $('#otp').prop('disabled', true);
            return;
        }
        
        const minutes = Math.floor(timeLeft / 60000);
        const seconds = Math.floor((timeLeft % 60000) / 1000);
        
        $('#otpTimer').html(`OTP expires in: ${minutes}:${seconds.toString().padStart(2, '0')}`);
        
        if (timeLeft < 60000) {
            $('#otpTimer').addClass('warning');
        }
    }, 1000);
}

// Email validation helper
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// =============================================
// Username/Email Check Functions
// =============================================

// Check username availability
function checkUsername(username) {
    $.ajax({
        url: checkUsernameUrl,
        type: 'POST',
        data: { username: username },
        success: function(response) {
            if (!response.available) {
                showAlert('error', response.message);
                $('#signup-username').focus();
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
        url: checkEmailUrl,
        type: 'POST',
        data: { email: email },
        success: function(response) {
            if (!response.available) {
                showAlert('error', response.message);
                $('#signup-email').focus();
            }
        },
        error: function(xhr) {
            console.error('Email check failed:', xhr);
        }
    });
}

// =============================================
// UI Helper Functions
// =============================================

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
    const icon = type === 'success' ?
        '<svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' :
        '<svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';

    const alert = `<div class="alert ${alertClass}">${icon}<span>${message}</span></div>`;
    
    // Determine which container to use based on which form is visible
    if ($('#container').hasClass('sign-in-state')) {
        // Login form is visible
        $('#alertContainer').html(alert);
    } else {
        // Register form is visible
        if ($('#registerAlertContainer').length) {
            $('#registerAlertContainer').html(alert);
        } else {
            $('#alertContainer').html(alert);
        }
    }

    // Auto hide after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut(300);
    }, 5000);
}

function toggleForm() {
    const container = $('#container');
    const signinForm = $('#signinForm');
    const signupForm = $('#signupForm');
    const forgotForm = $('#forgotForm');
    const formTitle = $('#formTitle');
    const formSubtitle = $('#formSubtitle');
    const welcomeTitle = $('#welcomeTitle');
    const welcomeMessage = $('#welcomeMessage');
    const toggleButton = $('#toggleButton');

    if (!forgotForm.hasClass('hidden')) {
        forgotForm.addClass('hidden');
    }

    container.addClass('swapping');
    toggleButton.addClass('loading');

    if (container.hasClass('sign-in-state')) {
        container.removeClass('sign-in-state').addClass('sign-up-state');
        
        formTitle.text('CREATE ACCOUNT');
        formSubtitle.text('Join us! Please fill in your details');
        welcomeTitle.text('Welcome Back!');
        welcomeMessage.text('Already have an account? Log in to continue');
        toggleButton.text('LOG IN');
    } else {
        container.removeClass('sign-up-state').addClass('sign-in-state');
        
        formTitle.text('LOG IN');
        formSubtitle.text('Welcome back! Please enter your details');
        welcomeTitle.text('Hello!');
        welcomeMessage.text('Register with your personal details to use all of site features');
        toggleButton.text('REGISTER');
    }

    if (container.hasClass('sign-in-state')) {
        signinForm.removeClass('hidden');
        signupForm.addClass('hidden');
    } else {
        signinForm.addClass('hidden');
        signupForm.removeClass('hidden');
    }

    toggleButton.removeClass('loading');
    
    // Clear alerts when switching forms
    $('#alertContainer').empty();
    if ($('#registerAlertContainer').length) {
        $('#registerAlertContainer').empty();
    }

    setTimeout(() => {
        container.removeClass('swapping');
    }, 600);
}

function showForgotPassword() {
    const container = $('#container');
    const signinForm = $('#signinForm');
    const signupForm = $('#signupForm');
    const forgotForm = $('#forgotForm');
    const formTitle = $('#formTitle');
    const formSubtitle = $('#formSubtitle');
    const welcomeTitle = $('#welcomeTitle');
    const welcomeMessage = $('#welcomeMessage');
    const toggleButton = $('#toggleButton');

    container.addClass('swapping');

    signinForm.addClass('hidden');
    signupForm.addClass('hidden');
    forgotForm.removeClass('hidden');

    formTitle.text('FORGOT PASSWORD');
    formSubtitle.text('Enter your email to reset your password');
    welcomeTitle.text('Reset Password');
    welcomeMessage.text('We\'ll send you instructions to reset your password');
    
    toggleButton.hide();
    
    $('#alertContainer').empty();
    if ($('#registerAlertContainer').length) {
        $('#registerAlertContainer').empty();
    }

    setTimeout(() => {
        container.removeClass('swapping');
    }, 300);
}

function showSignIn() {
    const container = $('#container');
    const signinForm = $('#signinForm');
    const signupForm = $('#signupForm');
    const forgotForm = $('#forgotForm');
    const formTitle = $('#formTitle');
    const formSubtitle = $('#formSubtitle');
    const welcomeTitle = $('#welcomeTitle');
    const welcomeMessage = $('#welcomeMessage');
    const toggleButton = $('#toggleButton');

    container.addClass('swapping');

    if (!container.hasClass('sign-in-state')) {
        container.removeClass('sign-up-state').addClass('sign-in-state');
    }

    signupForm.addClass('hidden');
    forgotForm.addClass('hidden');
    signinForm.removeClass('hidden');

    formTitle.text('LOG IN');
    formSubtitle.text('Welcome back! Please enter your details');
    welcomeTitle.text('Hello!');
    welcomeMessage.text('Register with your personal details to use all of site features');
    
    toggleButton.show();
    toggleButton.text('REGISTER');
    
    $('#alertContainer').empty();
    if ($('#registerAlertContainer').length) {
        $('#registerAlertContainer').empty();
    }

    setTimeout(() => {
        container.removeClass('swapping');
    }, 300);
}

function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
    
    const svg = button.querySelector('svg');
    if (type === 'text') {
        svg.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        button.classList.add('active');
    } else {
        svg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        button.classList.remove('active');
    }
}

function showLoading() {
    $('#loadingModal').addClass('show');
}

function hideLoading() {
    $('#loadingModal').removeClass('show');
}

// Make functions global
window.toggleForm = toggleForm;
window.showForgotPassword = showForgotPassword;
window.showSignIn = showSignIn;
window.showLoading = showLoading;
window.hideLoading = hideLoading;
window.sendOtp = sendOtp;
window.verifyOtp = verifyOtp;