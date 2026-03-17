<x-header title="Profile Settings" />

<style>
/* Additional styles for designation change button */
.designation-group {
    position: relative;
}

.designation-input-wrapper {
    display: flex;
    gap: 10px;
    align-items: center;
}

.designation-input-wrapper input {
    flex: 1;
}

.btn-change-designation {
    padding: 8px 16px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
}

.btn-change-designation:hover {
    background: linear-gradient(135deg, #d97706, #b45309);
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(245, 158, 11, 0.4);
}

.btn-change-designation:active {
    transform: translateY(0);
}

.btn-change-designation.editing {
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
}

.btn-change-designation.editing:hover {
    background: linear-gradient(135deg, #059669, #047857);
}
</style>

<main class="main-content">
    {{-- Breadcrumb Navigation --}}
    <div class="breadcrumb-container">
        <div class="breadcrumb">
            <a href="{{ $isOperator() ? route('operator.dashboard') : route('appointment.issuance') }}" class="breadcrumb-link">
                <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                Home
            </a>
            <svg class="breadcrumb-separator" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="breadcrumb-current">Profile Settings</span>
        </div>
        
        {{-- Back to Home Button --}}
        <a href="{{ $isOperator() ? route('operator.dashboard') : route('appointment.issuance') }}" class="back-button">
            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Home
        </a>
    </div>

    <div class="profile-container">
        {{-- Profile Header --}}
        <div class="profile-header">
            <div class="profile-header-content">
                <div class="profile-avatar-large">
                    {{ $displayName ? substr($displayName, 0, 1) : 'U' }}
                </div>
                <div class="profile-title">
                    <h1>Profile Settings</h1>
                    <p>Manage your account information and preferences</p>
                </div>
            </div>
        </div>

        <div class="profile-grid">
            {{-- Left Column - Profile Information --}}
            <div class="profile-card">
                <div class="profile-card-header">
                    <h3>
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        Profile Information
                    </h3>
                </div>
                <div class="profile-card-body">
                    <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                        @csrf
                        @method('PUT')
                        
                        {{-- Display Name (what users see) --}}
                        <div class="form-group">
                            <label for="name">Display Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $displayName) }}" 
                                   class="@error('name') is-invalid @enderror" 
                                   data-original="{{ $displayName }}"
                                   required>
                            <small class="field-hint">This is how you'll appear in the system</small>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Username (unique login identifier) --}}
                        <div class="form-group">
                            <label for="username">
                                Username
                                <span class="required-badge">Unique</span>
                            </label>
                            <div class="input-with-feedback">
                                <input type="text" name="username" id="username" 
                                       value="{{ old('username', $username) }}" 
                                       class="@error('username') is-invalid @enderror" 
                                       data-original="{{ $username }}"
                                       required>
                                <div class="input-feedback" id="username-feedback"></div>
                            </div>
                            <small class="field-hint">Username must be unique and cannot be changed frequently</small>
                            @error('username')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email (unique) --}}
                        <div class="form-group">
                            <label for="email">
                                Email Address
                                <span class="required-badge">Unique</span>
                            </label>
                            <div class="input-with-feedback">
                                <input type="email" name="email" id="email" 
                                       value="{{ old('email', $userEmail ?? '') }}" 
                                       class="@error('email') is-invalid @enderror"
                                       data-original="{{ $userEmail }}"
                                       required>
                                <div class="input-feedback" id="email-feedback"></div>
                            </div>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Designation Field with Change Button --}}
                        <div class="form-group designation-group">
                            <label for="designation">Designation</label>
                            <div class="designation-input-wrapper">
                                <input type="text" name="designation" id="designation" 
                                       value="{{ old('designation', $designation) }}" 
                                       class="@error('designation') is-invalid @enderror" 
                                       data-original="{{ $designation }}"
                                       readonly disabled>
                                <button type="button" class="btn-change-designation" id="changeDesignationBtn">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Change
                                </button>
                            </div>
                            <small class="field-hint">Click "Change" to edit your designation</small>
                            @error('designation')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        @if ($isOperator() && isset($windowNum) && $windowNum)
                        <div class="form-group">
                            <label for="window_num">Window Number</label>
                            <input type="text" name="window_num" id="window_num" value="Window #{{ $windowNum }}" 
                                   class="@error('window_num') is-invalid @enderror" readonly disabled>
                            <small class="field-hint">Window assignment cannot be changed</small>
                        </div>
                        @endif

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                                <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Right Column - Change Password --}}
            <div class="profile-card">
                <div class="profile-card-header">
                    <h3>
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        Change Password
                    </h3>
                </div>
                <div class="profile-card-body">
                    <form method="POST" action="{{ route('profile.password') }}" id="passwordForm">
                        @csrf
                        @method('PUT')
                        
                        {{-- Current Password with Show/Hide --}}
                        <div class="form-group password-field">
                            <label for="current_password">Current Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" name="current_password" id="current_password" 
                                       class="@error('current_password') is-invalid @enderror" required>
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('current_password', this)">
                                    <svg class="eye-icon" viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    <svg class="eye-slash-icon" viewBox="0 0 20 20" fill="currentColor" width="18" height="18" style="display: none;">
                                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                                        <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                                    </svg>
                                </button>
                            </div>
                            @error('current_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- New Password with Show/Hide --}}
                        <div class="form-group password-field">
                            <label for="new_password">New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" name="new_password" id="new_password" 
                                       class="@error('new_password') is-invalid @enderror" required>
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('new_password', this)">
                                    <svg class="eye-icon" viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    <svg class="eye-slash-icon" viewBox="0 0 20 20" fill="currentColor" width="18" height="18" style="display: none;">
                                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                                        <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                                    </svg>
                                </button>
                            </div>
                            @error('new_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Confirm New Password with Show/Hide --}}
                        <div class="form-group password-field">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required>
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('new_password_confirmation', this)">
                                    <svg class="eye-icon" viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    <svg class="eye-slash-icon" viewBox="0 0 20 20" fill="currentColor" width="18" height="18" style="display: none;">
                                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                                        <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
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

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Toggle password visibility function
function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const eyeIcon = button.querySelector('.eye-icon');
    const eyeSlashIcon = button.querySelector('.eye-slash-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.style.display = 'none';
        eyeSlashIcon.style.display = 'block';
    } else {
        input.type = 'password';
        eyeIcon.style.display = 'block';
        eyeSlashIcon.style.display = 'none';
    }
}

// Debounce function to limit API calls
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

document.addEventListener('DOMContentLoaded', function() {
    // Password validation
    const passwordInput = document.getElementById('new_password');
    const confirmInput = document.getElementById('new_password_confirmation');
    
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
    
    // Designation Change Functionality
    const changeDesignationBtn = document.getElementById('changeDesignationBtn');
    const designationInput = document.getElementById('designation');
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const submitBtn = document.getElementById('submit-btn');
    
    let isDesignationEditing = false;
    let originalDesignation = designationInput?.value;
    
    // Get original values from data attributes
    const nameOriginal = nameInput?.dataset.original;
    const usernameOriginal = usernameInput?.dataset.original;
    const emailOriginal = emailInput?.dataset.original;
    
    let isUsernameValid = true;
    let isEmailValid = true;

    // Update submit button state
    function updateSubmitButton() {
        if (submitBtn) {
            // Check if ANY field has changed
            const nameChanged = nameInput && nameInput.value !== nameOriginal;
            const usernameChanged = usernameInput && usernameInput.value !== usernameOriginal;
            const emailChanged = emailInput && emailInput.value !== emailOriginal;
            const designationChanged = designationInput && designationInput.value !== originalDesignation && !designationInput.readOnly;
            
            const hasChanges = nameChanged || usernameChanged || emailChanged || designationChanged;
            
            // Enable button if there are changes AND all validations pass
            submitBtn.disabled = !(hasChanges && isUsernameValid && isEmailValid);
        }
    }

    // Add change detection for name input
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            updateSubmitButton();
        });
    }

    // Simplified username validation
    if (usernameInput) {
        const usernameFeedback = document.getElementById('username-feedback');
        
        const checkUsername = debounce(function() {
            const username = usernameInput.value;
            
            if (username === usernameOriginal) {
                usernameFeedback.innerHTML = '';
                usernameFeedback.className = 'input-feedback';
                isUsernameValid = true;
                updateSubmitButton();
                return;
            }
            
            if (username.length < 3) {
                usernameFeedback.innerHTML = '❌ Too short (min 3 chars)';
                usernameFeedback.className = 'input-feedback invalid';
                isUsernameValid = false;
                updateSubmitButton();
                return;
            }
            
            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                usernameFeedback.innerHTML = '❌ Only letters, numbers, underscore';
                usernameFeedback.className = 'input-feedback invalid';
                isUsernameValid = false;
                updateSubmitButton();
                return;
            }
            
            // Valid format
            usernameFeedback.innerHTML = '';
            usernameFeedback.className = 'input-feedback';
            isUsernameValid = true;
            updateSubmitButton();
            
        }, 500);
        
        usernameInput.addEventListener('input', checkUsername);
    }

    // Check email uniqueness
    if (emailInput) {
        const emailFeedback = document.getElementById('email-feedback');
        
        const checkEmail = debounce(async function() {
            const email = emailInput.value;
            
            if (email === emailOriginal) {
                emailFeedback.innerHTML = '';
                emailFeedback.className = 'input-feedback';
                isEmailValid = true;
                updateSubmitButton();
                return;
            }
            
            if (!email.includes('@') || !email.includes('.')) {
                emailFeedback.innerHTML = '❌ Invalid email format';
                emailFeedback.className = 'input-feedback invalid';
                isEmailValid = false;
                updateSubmitButton();
                return;
            }
            
            emailFeedback.innerHTML = '⏳ Checking...';
            emailFeedback.className = 'input-feedback checking';
            
            try {
                const response = await fetch('/check-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ email, current: emailOriginal })
                });
                
                const data = await response.json();
                
                if (data.available) {
                    emailFeedback.innerHTML = '✓ Available';
                    emailFeedback.className = 'input-feedback valid';
                    isEmailValid = true;
                } else {
                    emailFeedback.innerHTML = '✗ Already in use';
                    emailFeedback.className = 'input-feedback invalid';
                    isEmailValid = false;
                }
            } catch (error) {
                emailFeedback.innerHTML = '';
                emailFeedback.className = 'input-feedback';
                isEmailValid = true; // Allow submission even if check fails
            }
            
            updateSubmitButton();
        }, 500);
        
        emailInput.addEventListener('input', checkEmail);
    }

    // Designation change button logic
    if (changeDesignationBtn && designationInput) {
        changeDesignationBtn.addEventListener('click', function() {
            if (!isDesignationEditing) {
                // First click - ask for confirmation to edit
                Swal.fire({
                    title: 'Change Designation?',
                    text: 'Are you sure you want to change your designation? This might affect your system permissions.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, edit',
                    cancelButtonText: 'No, keep'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Enable editing
                        designationInput.readOnly = false;
                        designationInput.disabled = false;
                        designationInput.focus();
                        changeDesignationBtn.innerHTML = `
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Save Designation
                        `;
                        changeDesignationBtn.classList.add('editing');
                        isDesignationEditing = true;
                        
                        // Show hint that editing is enabled
                        Swal.fire({
                            title: 'Editing Enabled',
                            text: 'You can now edit your designation. Click "Save Designation" when done.',
                            icon: 'info',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                });
            } else {
                // Second click - save the changes
                const newDesignation = designationInput.value.trim();
                
                if (newDesignation === originalDesignation) {
                    // No changes made, just revert
                    designationInput.readOnly = true;
                    designationInput.disabled = true;
                    changeDesignationBtn.innerHTML = `
                        <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Change
                    `;
                    changeDesignationBtn.classList.remove('editing');
                    isDesignationEditing = false;
                    
                    Swal.fire({
                        title: 'No Changes',
                        text: 'No changes were made to your designation.',
                        icon: 'info',
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                    return;
                }
                
                if (newDesignation === '') {
                    Swal.fire({
                        title: 'Invalid Designation',
                        text: 'Designation cannot be empty.',
                        icon: 'error',
                        confirmButtonColor: '#dc2626'
                    });
                    return;
                }
                
                // Confirm save
                Swal.fire({
                    title: 'Save Designation?',
                    html: `Are you sure you want to change your designation from <strong>${originalDesignation}</strong> to <strong>${newDesignation}</strong>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, save',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form with the new designation
                        const form = document.getElementById('profileForm');
                        
                        // Show loading
                        Swal.fire({
                            title: 'Saving...',
                            html: 'Please wait while we update your designation',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit the form
                        form.submit();
                    } else {
                        // User cancelled, revert to read-only but keep the edited value
                        designationInput.readOnly = true;
                        designationInput.disabled = true;
                        changeDesignationBtn.innerHTML = `
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Change
                        `;
                        changeDesignationBtn.classList.remove('editing');
                        isDesignationEditing = false;
                    }
                });
            }
        });
    }

    // Add change detection for designation input when editable
    if (designationInput) {
        designationInput.addEventListener('input', function() {
            updateSubmitButton();
        });
    }
    
    // Form submission with confirmation
    const profileForm = document.getElementById('profileForm');
    const passwordForm = document.getElementById('passwordForm');
    
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!isUsernameValid || !isEmailValid) {
                Swal.fire({
                    title: 'Validation Error',
                    text: 'Please fix the username or email issues before saving.',
                    icon: 'error',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }
            
            Swal.fire({
                title: 'Save Changes?',
                text: 'Are you sure you want to update your profile information?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#dc2626',
                confirmButtonText: 'Yes, save',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Saving...',
                        html: 'Please wait while we update your profile',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit form
                    profileForm.submit();
                }
            });
        });
    }
    
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('new_password').value;
            const confirm = document.getElementById('new_password_confirmation').value;
            
            // Validate password requirements
            const isValid = 
                password.length >= 8 &&
                /[A-Z]/.test(password) &&
                /[a-z]/.test(password) &&
                /[0-9]/.test(password) &&
                /[!@#$%^&*(),.?":{}|<>]/.test(password);
            
            if (!isValid) {
                Swal.fire({
                    title: 'Invalid Password',
                    text: 'Please make sure your password meets all requirements',
                    icon: 'error',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }
            
            if (password !== confirm) {
                Swal.fire({
                    title: 'Passwords Do Not Match',
                    text: 'Please make sure your passwords match',
                    icon: 'error',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }
            
            Swal.fire({
                title: 'Change Password?',
                text: 'Are you sure you want to change your password?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#dc2626',
                confirmButtonText: 'Yes, change',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Updating...',
                        html: 'Please wait while we update your password',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit form
                    passwordForm.submit();
                }
            });
        });
    }
    
    // Show success message if exists
    @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#2563eb',
            timer: 3000,
            timerProgressBar: true,
            showCloseButton: true
        });
    @endif
    
    // Show error message if exists
    @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#dc2626',
            timer: 5000,
            timerProgressBar: true,
            showCloseButton: true
        });
    @endif
    
    @if($errors->any())
        let errorHtml = '<ul style="text-align: left;">';
        @foreach($errors->all() as $error)
            errorHtml += '<li>{{ $error }}</li>';
        @endforeach
        errorHtml += '</ul>';
        
        Swal.fire({
            title: 'Validation Error',
            html: errorHtml,
            icon: 'error',
            confirmButtonColor: '#dc2626'
        });
    @endif
});
</script>