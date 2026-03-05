<x-header title="Profile Settings" />

<main class="main-content">
    {{-- Breadcrumb Navigation --}}
    <div class="breadcrumb-container">
        <div class="breadcrumb">
            <a href="{{ $isOperator() ? route('operator.dashboard') : route('appointment.issuance') }}" class="breadcrumb-link">
                <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                Dashboard
            </a>
            <svg class="breadcrumb-separator" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="breadcrumb-current">Profile Settings</span>
        </div>
        
        {{-- Back to Dashboard Button --}}
        <a href="{{ $isOperator() ? route('operator.dashboard') : route('appointment.issuance') }}" class="back-button">
            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="profile-container">
        {{-- Profile Header --}}
        <div class="profile-header">
            <div class="profile-header-content">
                <div class="profile-avatar-large">
                    {{ $userName ? substr($userName, 0, 1) : 'U' }}
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
                        
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $userName) }}" 
                                   class="@error('name') is-invalid @enderror" required>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $userEmail ?? '') }}" 
                                   class="@error('email') is-invalid @enderror" required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="designation">Designation</label>
                            <input type="text" name="designation" id="designation" value="{{ old('designation', $designation) }}" 
                                   class="@error('designation') is-invalid @enderror" readonly disabled>
                            <small class="field-hint">Designation cannot be changed</small>
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
                            <button type="submit" class="btn btn-primary">
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

<style>
/* Breadcrumb Styles */
.breadcrumb-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 0 5px;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.breadcrumb-link {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--gray-600);
    text-decoration: none;
    padding: 6px 10px;
    border-radius: var(--radius);
    transition: all 0.2s ease;
    background: var(--gray-100);
}

.breadcrumb-link:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
}

.breadcrumb-link:hover svg {
    color: white;
}

.breadcrumb-link svg {
    color: var(--primary);
    transition: color 0.2s ease;
}

.breadcrumb-separator {
    color: var(--gray-400);
}

.breadcrumb-current {
    color: var(--gray-800);
    font-weight: 500;
    padding: 6px 10px;
    background: var(--gray-100);
    border-radius: var(--radius);
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: white;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    color: var(--gray-700);
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.back-button:hover {
    background: var(--gray-50);
    border-color: var(--primary);
    color: var(--primary);
    transform: translateX(-2px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.back-button svg {
    transition: transform 0.2s ease;
}

.back-button:hover svg {
    transform: translateX(-2px);
}

/* Profile Page Styles */
.main-content {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 30px;
}

.profile-container {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.profile-header {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: var(--radius);
    padding: 40px;
    color: white;
    margin-bottom: 20px;
    box-shadow: var(--shadow-md);
}

.profile-header-content {
    display: flex;
    align-items: center;
    gap: 30px;
}

.profile-avatar-large {
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: 600;
    color: white;
    border: 4px solid rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(10px);
}

.profile-title h1 {
    font-size: 32px;
    font-weight: 700;
    margin: 0 0 8px 0;
}

.profile-title p {
    font-size: 16px;
    opacity: 0.9;
    margin: 0;
}

.profile-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.profile-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: all 0.2s ease;
}

.profile-card:hover {
    box-shadow: var(--shadow-md);
}

.profile-card.full-width {
    grid-column: 1 / -1;
}

.profile-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--gray-200);
    background: var(--gray-50);
}

.profile-card-header h3 {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.profile-card-header h3 svg {
    width: 22px;
    height: 22px;
    color: var(--primary);
}

.profile-card-body {
    padding: 24px;
}

/* Form Styles */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: var(--gray-700);
    margin-bottom: 6px;
}

.form-group input {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    font-size: 14px;
    transition: all 0.2s ease;
    background: white;
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-group input[readonly],
.form-group input[disabled] {
    background: var(--gray-100);
    color: var(--gray-600);
    cursor: not-allowed;
    border-color: var(--gray-300);
}

/* Password Field Styles */
.password-field .password-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.password-field input {
    padding-right: 45px;
}

.toggle-password {
    position: absolute;
    right: 10px;
    background: none;
    border: none;
    cursor: pointer;
    color: var(--gray-500);
    padding: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s ease;
}

.toggle-password:hover {
    color: var(--primary);
}

.toggle-password svg {
    width: 18px;
    height: 18px;
}

.field-hint {
    display: block;
    font-size: 12px;
    color: var(--gray-500);
    margin-top: 4px;
}

.error-message {
    display: block;
    font-size: 12px;
    color: var(--danger);
    margin-top: 4px;
}

.form-actions {
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid var(--gray-200);
}

.btn {
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 500;
    border-radius: var(--radius);
    transition: all 0.2s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
}

.btn-primary:active {
    transform: translateY(0);
}

/* Password Requirements */
.password-requirements {
    background: var(--gray-50);
    border-radius: var(--radius);
    padding: 16px;
    margin: 20px 0;
    border: 1px solid var(--gray-200);
}

.password-requirements p {
    font-size: 13px;
    font-weight: 600;
    color: var(--gray-700);
    margin: 0 0 10px 0;
}

.password-requirements ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.requirement {
    font-size: 13px;
    color: var(--gray-600);
    padding: 4px 0 4px 24px;
    position: relative;
}

.requirement::before {
    content: '○';
    position: absolute;
    left: 0;
    color: var(--gray-400);
}

.requirement.valid {
    color: var(--success);
}

.requirement.valid::before {
    content: '✓';
    color: var(--success);
}

/* Responsive Design */
@media (max-width: 768px) {
    .breadcrumb-container {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .profile-header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .profile-grid {
        grid-template-columns: 1fr;
    }
    
    .profile-avatar-large {
        width: 80px;
        height: 80px;
        font-size: 36px;
    }
    
    .profile-title h1 {
        font-size: 24px;
    }
}

@media (max-width: 640px) {
    .main-content {
        padding: 0 15px;
    }
    
    .profile-header {
        padding: 30px 20px;
    }
    
    .profile-card-body {
        padding: 20px;
    }
    
    .breadcrumb {
        flex-wrap: wrap;
    }
}
</style>

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
    
    // Form submission with confirmation
    const profileForm = document.getElementById('profileForm');
    const passwordForm = document.getElementById('passwordForm');
    
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
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