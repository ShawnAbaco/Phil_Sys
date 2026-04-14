@extends('layouts.admin')

@section('title', 'Add New User')
@section('pageTitle', 'Add New User')
@section('pageSubtitle', 'Create a new system user account')

@php $activeMenu = 'users'; @endphp

@section('content')
    <div class="form-container">
        <div class="form-card">
            <div class="form-card-header">
                <h3>User Information</h3>
                <p>Enter the details for the new user account</p>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" id="createUserForm">
                @csrf

                <div class="form-section">
                    <h4>Personal Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Full Name <span class="required">*</span></label>
                            <input type="text" id="full_name" name="full_name"
                                class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}"
                                required>
                            @error('full_name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="username">Username <span class="required">*</span></label>
                            <input type="text" id="username" name="username"
                                class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}"
                                required>
                            @error('username')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" id="contact_number" name="contact_number" class="form-control"
                                value="{{ old('contact_number') }}">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4>Account Details</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="designation">Designation / Role <span class="required">*</span></label>
                            <select id="designation" name="designation"
                                class="form-control @error('designation') is-invalid @enderror" required
                                onchange="toggleWindowField()">
                                <option value="">Select Role</option>
                                <option value="Screener" {{ old('designation') == 'Screener' ? 'selected' : '' }}>Screener
                                </option>
                                <option value="Registration Kit Operator"
                                    {{ old('designation') == 'Registration Kit Operator' ? 'selected' : '' }}>Registration
                                    Kit Operator</option>
                                <option value="Registration Assistant"
                                    {{ old('designation') == 'Registration Assistant' ? 'selected' : '' }}>Registration
                                    Assistant</option>
                                <option value="Administrator"
                                    {{ old('designation') == 'Administrator' ? 'selected' : '' }}>Administrator</option>
                                <option value="Super Administrator"
                                    {{ old('designation') == 'Super Administrator' ? 'selected' : '' }}>Super Administrator
                                </option>
                                <option value="Operations Manager"
                                    {{ old('designation') == 'Operations Manager' ? 'selected' : '' }}>Operations Manager
                                </option>
                                <option value="Team Supervisor"
                                    {{ old('designation') == 'Team Supervisor' ? 'selected' : '' }}>Team Supervisor
                                </option>
                                <option value="Technical Support"
                                    {{ old('designation') == 'Technical Support' ? 'selected' : '' }}>Technical Support
                                </option>
                            </select>
                            @error('designation')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group" id="windowField" style="display: none;">
                            <label for="window_num">Window Number</label>
                            <select id="window_num" name="window_num"
                                class="form-control @error('window_num') is-invalid @enderror">
                                <option value="">Not Assigned</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('window_num') == $i ? 'selected' : '' }}>
                                        Window {{ $i }}</option>
                                @endfor
                            </select>
                            @error('window_num')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Temporary Password</label>
                            <div class="password-field">
                                <input type="text" id="password" name="password" class="form-control"
                                    value="{{ Str::random(12) }}" readonly>
                                <button type="button" class="btn-icon" onclick="generateNewPassword()"
                                    title="Generate new password">
                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <small class="form-text">This password will be shown to the user on first login</small>
                        </div>
                        <div class="form-group">
                            <label for="status">Account Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4>Additional Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" class="form-control"
                                value="{{ old('birthdate') }}">
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" class="form-control">
                                <option value="">Prefer not to say</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>

        <div class="info-card">
            <h4>Password Information</h4>
            <ul class="info-list">
                <li>Password is auto-generated and should be changed on first login</li>
                <li>User will be prompted to change password upon first login</li>
                <li>Password must meet the following criteria:
                    <ul>
                        <li>At least 8 characters long</li>
                        <li>Contains uppercase and lowercase letters</li>
                        <li>Contains at least one number</li>
                        <li>Contains at least one special character</li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleWindowField() {
                const designation = document.getElementById('designation').value;
                const windowField = document.getElementById('windowField');

                if (designation.includes('Operator') || designation.includes('operator')) {
                    windowField.style.display = 'block';
                } else {
                    windowField.style.display = 'none';
                    document.getElementById('window_num').value = '';
                }
            }

            function generateNewPassword() {
                const length = 12;
                const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
                let password = '';
                for (let i = 0; i < length; i++) {
                    password += charset.charAt(Math.floor(Math.random() * charset.length));
                }
                document.getElementById('password').value = password;
            }

            document.addEventListener('DOMContentLoaded', function() {
                toggleWindowField();
            });
        </script>
    @endpush
@endsection
