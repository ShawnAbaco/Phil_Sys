$(document).ready(function() {
    // Set CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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
});

function initializeForms() {
    // Login Form - Traditional submission
    $('#loginForm').on('submit', function(e) {
        showLoading();
        return true; // Allow traditional form submission
    });

    // Register Form - FIXED: Use traditional submission instead of AJAX
    $('#signupFormElement').on('submit', function(e) {
        showLoading();
        return true; // Allow traditional form submission - controller will redirect
    });

    // Forgot Password Form - Keep AJAX if you want
    $('#forgotFormElement').on('submit', function(e) {
        e.preventDefault();

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
                    showAlert('error', 'Username not found in our records.');
                } else if (xhr.status === 0) {
                    // Connection error - fallback to traditional submission
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
        }
    });
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
    const icon = type === 'success' ?
        '<svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' :
        '<svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';

    const alert = `<div class="alert ${alertClass}">${icon}<span>${message}</span></div>`;
    $('#alertContainer').html(alert);

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

    // Add swapping class - content starts fading
    container.addClass('swapping');
    toggleButton.addClass('loading');

    // Immediately start panel swap (no delay)
    if (container.hasClass('sign-in-state')) {
        // Switch to sign up
        container.removeClass('sign-in-state').addClass('sign-up-state');
        
        // Update text content immediately
        formTitle.text('CREATE ACCOUNT');
        formSubtitle.text('Join us! Please fill in your details');
        welcomeTitle.text('Welcome Back!');
        welcomeMessage.text('Already have an account? Log in to continue');
        toggleButton.text('LOG IN');
    } else {
        // Switch to sign in
        container.removeClass('sign-up-state').addClass('sign-in-state');
        
        // Update text content immediately
        formTitle.text('LOG IN');
        formSubtitle.text('Welcome back! Please enter your details');
        welcomeTitle.text('Hello!');
        welcomeMessage.text('Register with your personal details to use all of site features');
        toggleButton.text('REGISTER');
    }

    // Update form visibility
    if (container.hasClass('sign-in-state')) {
        signinForm.removeClass('hidden');
        signupForm.addClass('hidden');
    } else {
        signinForm.addClass('hidden');
        signupForm.removeClass('hidden');
    }

    toggleButton.removeClass('loading');
    $('#alertContainer').empty();

    // Remove swapping class after animation completes
    setTimeout(() => {
        container.removeClass('swapping');
    }, 600); // Match panel transition time (0.6s)
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

    // Add swapping class for animation - fade out all content
    container.addClass('swapping');

    // Hide all forms and update text immediately
    signinForm.addClass('hidden');
    signupForm.addClass('hidden');
    forgotForm.removeClass('hidden');

    formTitle.text('FORGOT PASSWORD');
    formSubtitle.text('Enter your username to reset your password');
    welcomeTitle.text('Reset Password');
    welcomeMessage.text('We\'ll send you instructions to reset your password');
    
    // Hide toggle button on welcome panel
    toggleButton.hide();
    
    $('#alertContainer').empty();

    // Remove swapping class after animation
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

    // Add swapping class for animation - fade out all content
    container.addClass('swapping');

    // Immediately update state and content
    if (!container.hasClass('sign-in-state')) {
        container.removeClass('sign-up-state').addClass('sign-in-state');
    }

    // Hide all forms
    signupForm.addClass('hidden');
    forgotForm.addClass('hidden');
    signinForm.removeClass('hidden');

    formTitle.text('LOG IN');
    formSubtitle.text('Welcome back! Please enter your details');
    welcomeTitle.text('Hello!');
    welcomeMessage.text('Register with your personal details to use all of site features');
    
    // Show toggle button again
    toggleButton.show();
    toggleButton.text('REGISTER');
    
    $('#alertContainer').empty();

    // Remove swapping class after animation
    setTimeout(() => {
        container.removeClass('swapping');
    }, 300);
}

// Password show/hide functionality
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
    
    // Toggle the eye icon
    const svg = button.querySelector('svg');
    if (type === 'text') {
        // Eye with slash icon (password visible)
        svg.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        button.classList.add('active');
    } else {
        // Regular eye icon (password hidden)
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