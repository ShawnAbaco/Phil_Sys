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
        });

        function initializeForms() {
            // Login Form - Traditional submission as fallback
            $('#loginForm').on('submit', function(e) {
                // Don't prevent default - let it submit traditionally
                // This ensures it works even without AJAX
                showLoading();
                return true; // Allow traditional form submission
            });

            // Register Form
            $('#signupFormElement').on('submit', function(e) {
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
                                // Clear form
                                $('#signupFormElement')[0].reset();
                                showSignIn();
                            }, 1500);
                        } else {
                            showAlert('error', response.message || 'Registration failed');
                        }
                    },
                    error: function(xhr) {
                        hideLoading();

                        if (xhr.status === 422) {
                            const response = xhr.responseJSON;
                            if (response && response.errors) {
                                const errors = response.errors;
                                let errorMessage = '';
                                for (let key in errors) {
                                    errorMessage += errors[key][0] + '<br>';
                                }
                                showAlert('error', errorMessage);
                            } else {
                                showAlert('error', 'Validation error');
                            }
                        } else if (xhr.status === 0) {
                            // Connection error - fallback to traditional submission
                            hideLoading();
                            if (confirm('Connection error. Submit form traditionally?')) {
                                $('#signupFormElement')[0].submit();
                            }
                        } else {
                            showAlert('error', 'Registration failed. Please try again.');
                        }
                    }
                });
            });

            // Forgot Password Form
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

            toggleButton.addClass('loading');

            setTimeout(() => {
                if (container.hasClass('sign-in-state')) {
                    container.removeClass('sign-in-state').addClass('sign-up-state');
                    signinForm.addClass('hidden');
                    signupForm.removeClass('hidden');

                    formTitle.text('Create Account');
                    formSubtitle.text('Enter your details to get started');
                    welcomeTitle.text('Welcome Back!');
                    welcomeMessage.text('Sign in to access your account');
                    toggleButton.text('SIGN IN');
                } else {
                    container.removeClass('sign-up-state').addClass('sign-in-state');
                    signinForm.removeClass('hidden');
                    signupForm.addClass('hidden');

                    formTitle.text('Sign In');
                    formSubtitle.text('Welcome back! Please enter your details');
                    welcomeTitle.text('Hello, Friend!');
                    welcomeMessage.text('Register with your personal details to use all of site features');
                    toggleButton.text('SIGN UP');
                }

                toggleButton.removeClass('loading');
                $('#alertContainer').empty();
            }, 300);
        }

        function showForgotPassword() {
            const signinForm = $('#signinForm');
            const signupForm = $('#signupForm');
            const forgotForm = $('#forgotForm');
            const formTitle = $('#formTitle');
            const formSubtitle = $('#formSubtitle');

            signinForm.addClass('hidden');
            signupForm.addClass('hidden');
            forgotForm.removeClass('hidden');

            formTitle.text('Forgot Password');
            formSubtitle.text('Enter your username to reset your password');
            $('#alertContainer').empty();
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

            if (!container.hasClass('sign-in-state')) {
                container.removeClass('sign-up-state').addClass('sign-in-state');
            }

            signupForm.addClass('hidden');
            forgotForm.addClass('hidden');
            signinForm.removeClass('hidden');

            formTitle.text('Sign In');
            formSubtitle.text('Welcome back! Please enter your details');
            welcomeTitle.text('Hello, Friend!');
            welcomeMessage.text('Register with your personal details to use all of site features');
            toggleButton.text('SIGN IN');
            $('#alertContainer').empty();
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