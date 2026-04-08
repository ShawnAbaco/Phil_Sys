{{-- resources/views/screener/appointments.blade.php --}}
<x-header title="Appointments" />

<div class="app-container">
    <x-screener.sidebar />

    <main class="main-content">
        <!-- Page Header with Breadcrumb -->
        <div class="page-header">
            <div class="page-title1">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <h1>Appointments</h1>
            </div>
            <div class="breadcrumb">
                <a href="{{ route('screener.dashboard') }}">Home</a>
                <span>/</span>
                <span>Appointments</span>
            </div>
        </div>

        <div class="message-container" id="messageContainer"></div>

        <div class="dashboard-grid">
            {{-- Issue New Appointment Card --}}
            <div class="card">
                <div class="card-header">
                    <h3>
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                clip-rule="evenodd" />
                        </svg>
                        Issue New Appointment
                    </h3>
                </div>
                <div class="card-body">
                    {{-- Category Selection Buttons --}}
                    <div style="margin-bottom: 25px;">
                        <label
                            style="display: block; margin-bottom: 10px; font-weight: 600; color: #1f2937; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em;">
                            Select Category:
                        </label>
                        <div style="display: flex; gap: 12px; margin-top: 5px;">
                            <button type="button"
                                class="category-btn {{ old('category', session('last_category')) == 'NID Registration' ? 'active' : '' }}"
                                data-category="NID Registration" onclick="selectCategory('NID Registration')">
                                <span style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                                        <path
                                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                    NID Registration
                                </span>
                            </button>
                            <button type="button"
                                class="category-btn {{ old('category', session('last_category')) == 'Status Inquiry' ? 'active' : '' }}"
                                data-category="Status Inquiry" onclick="selectCategory('Status Inquiry')">
                                <span style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Status Inquiry
                                </span>
                            </button>
                            <button type="button"
                                class="category-btn {{ old('category', session('last_category')) == 'Updating' ? 'active' : '' }}"
                                data-category="Updating" onclick="selectCategory('Updating')">
                                <span style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                        <path fill-rule="evenodd"
                                            d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Updating
                                </span>
                            </button>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('appointment.issue') }}" id="appointmentForm"
                        accept-charset="UTF-8" novalidate>
                        @csrf
                        <input type="hidden" name="category" id="selectedCategory"
                            value="{{ old('category', session('last_category', 'NID Registration')) }}">

                        {{-- NID Registration Form --}}
                        <div id="nidForm" class="category-form"
                            style="display: {{ old('category', session('last_category')) == 'NID Registration' ? 'block' : 'none' }};">
                            <div style="border-left: 4px solid #2563eb; padding-left: 15px; margin-bottom: 20px;">
                                <h4 style="margin: 0 0 5px 0; font-size: 16px;">NID Registration Details</h4>
                                <p style="margin: 0; color: #6b7280; font-size: 13px;">Please fill in the required
                                    information below</p>
                            </div>

                            <div class="form-group" style="margin-top: 15px; margin-bottom: 20px;">
                                <label>Priority Type <span style="color: #dc2626;">*</span></label>
                                <div class="priority-buttons">
                                    <input type="hidden" name="priority_type_nid" id="priority_type_nid"
                                        value="{{ old('priority_type_nid', session('last_priority_nid', 'regular')) }}"
                                        required>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'senior' ? 'active' : '' }}"
                                        data-priority="senior"
                                        onclick="selectPriorityType('nid', 'senior')">Senior</button>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'infant' ? 'active' : '' }}"
                                        data-priority="infant"
                                        onclick="selectPriorityType('nid', 'infant')">Infant</button>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'pwd' ? 'active' : '' }}"
                                        data-priority="pwd" onclick="selectPriorityType('nid', 'pwd')">PWD</button>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'pregnant' ? 'active' : '' }}"
                                        data-priority="pregnant"
                                        onclick="selectPriorityType('nid', 'pregnant')">Pregnant</button>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'regular' ? 'active' : '' }}"
                                        data-priority="regular"
                                        onclick="selectPriorityType('nid', 'regular')">Regular</button>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fname_nid">First Name <span style="color: #dc2626;">*</span></label>
                                    <input type="text" name="fname_nid" id="fname_nid"
                                        value="{{ old('fname_nid') }}" pattern="[A-Za-zÑñ\s\-']+"
                                        title="Only letters, spaces, hyphens, and apostrophes are allowed"
                                        placeholder="Enter first name" required>
                                </div>
                                <div class="form-group">
                                    <label for="mname_nid">Middle Name</label>
                                    <input type="text" name="mname_nid" id="mname_nid"
                                        value="{{ old('mname_nid') }}" pattern="[A-Za-zÑñ\s\-']*"
                                        placeholder="Enter middle name">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="lname_nid">Last Name <span style="color: #dc2626;">*</span></label>
                                    <input type="text" name="lname_nid" id="lname_nid"
                                        value="{{ old('lname_nid') }}" pattern="[A-Za-zÑñ\s\-']+"
                                        placeholder="Enter last name" required>
                                </div>
                                <div class="form-group">
                                    <label for="suffix_nid">Suffix</label>
                                    <input type="text" name="suffix_nid" id="suffix_nid"
                                        value="{{ old('suffix_nid') }}" pattern="[A-Za-zÑñ\s\-'.]*"
                                        placeholder="Jr., Sr., III">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="age_category_nid">Age Category <span
                                            style="color: #dc2626;">*</span></label>
                                    <div class="age-category-buttons">
                                        <input type="hidden" name="age_category_nid" id="age_category_nid"
                                            value="{{ old('age_category_nid') }}" required>
                                        <button type="button"
                                            class="age-btn {{ old('age_category_nid') == '0-4 years old' ? 'active' : '' }}"
                                            data-age="0-4 years old" onclick="selectAge('nid', '0-4 years old')">0-4
                                            years old</button>
                                        <button type="button"
                                            class="age-btn {{ old('age_category_nid') == '5 years old and above' ? 'active' : '' }}"
                                            data-age="5 years old and above"
                                            onclick="selectAge('nid', '5 years old and above')">5 years old and
                                            above</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="birthdate_nid">Birthdate <span
                                            style="color: #dc2626;">*</span></label>
                                    <input type="date" name="birthdate_nid" id="birthdate_nid"
                                        value="{{ old('birthdate_nid') }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Status Inquiry Form --}}
                        <div id="statusForm" class="category-form"
                            style="display: {{ old('category', session('last_category')) == 'Status Inquiry' ? 'block' : 'none' }};">
                            <div style="border-left: 4px solid #2563eb; padding-left: 15px; margin-bottom: 20px;">
                                <h4 style="margin: 0 0 5px 0; font-size: 16px;">Status Inquiry Details</h4>
                                <p style="margin: 0; color: #6b7280; font-size: 13px;">Please fill in the required
                                    information below</p>
                            </div>

                            <div class="form-group" style="margin-top: 15px; margin-bottom: 20px;">
                                <label>Priority Type <span style="color: #dc2626;">*</span></label>
                                <div class="priority-buttons">
                                    <input type="hidden" name="priority_type_status" id="priority_type_status"
                                        value="{{ old('priority_type_status', session('last_priority_status', 'regular')) }}"
                                        required>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'senior' ? 'active' : '' }}"
                                        data-priority="senior"
                                        onclick="selectPriorityType('status', 'senior')">Senior</button>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'infant' ? 'active' : '' }}"
                                        data-priority="infant"
                                        onclick="selectPriorityType('status', 'infant')">Infant</button>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'pwd' ? 'active' : '' }}"
                                        data-priority="pwd" onclick="selectPriorityType('status', 'pwd')">PWD</button>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'pregnant' ? 'active' : '' }}"
                                        data-priority="pregnant"
                                        onclick="selectPriorityType('status', 'pregnant')">Pregnant</button>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'regular' ? 'active' : '' }}"
                                        data-priority="regular"
                                        onclick="selectPriorityType('status', 'regular')">Regular</button>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fname_status">First Name <span
                                            style="color: #dc2626;">*</span></label>
                                    <input type="text" name="fname_status" id="fname_status"
                                        value="{{ old('fname_status') }}" pattern="[A-Za-zÑñ\s\-']+"
                                        placeholder="Enter first name" required>
                                </div>
                                <div class="form-group">
                                    <label for="mname_status">Middle Name</label>
                                    <input type="text" name="mname_status" id="mname_status"
                                        value="{{ old('mname_status') }}" pattern="[A-Za-zÑñ\s\-']*"
                                        placeholder="Enter middle name">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="lname_status">Last Name <span style="color: #dc2626;">*</span></label>
                                    <input type="text" name="lname_status" id="lname_status"
                                        value="{{ old('lname_status') }}" pattern="[A-Za-zÑñ\s\-']+"
                                        placeholder="Enter last name" required>
                                </div>
                                <div class="form-group">
                                    <label for="suffix_status">Suffix</label>
                                    <input type="text" name="suffix_status" id="suffix_status"
                                        value="{{ old('suffix_status') }}" pattern="[A-Za-zÑñ\s\-'.]*"
                                        placeholder="Jr., Sr., III">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="age_category_status">Age Category <span
                                            style="color: #dc2626;">*</span></label>
                                    <div class="age-category-buttons">
                                        <input type="hidden" name="age_category_status" id="age_category_status"
                                            value="{{ old('age_category_status') }}" required>
                                        <button type="button"
                                            class="age-btn {{ old('age_category_status') == '0-4 years old' ? 'active' : '' }}"
                                            data-age="0-4 years old"
                                            onclick="selectAge('status', '0-4 years old')">0-4 years old</button>
                                        <button type="button"
                                            class="age-btn {{ old('age_category_status') == '5 years old and above' ? 'active' : '' }}"
                                            data-age="5 years old and above"
                                            onclick="selectAge('status', '5 years old and above')">5 years old and
                                            above</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="birthdate_status">Birthdate <span
                                            style="color: #dc2626;">*</span></label>
                                    <input type="date" name="birthdate_status" id="birthdate_status"
                                        value="{{ old('birthdate_status') }}" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group" style="flex: 2;">
                                    <label for="trn">Transaction Reference Number (TRN)</label>
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <input type="text" name="trn" id="trn"
                                            value="{{ old('trn') }}"
                                            placeholder="Scan QR code or type TRN manually" autocomplete="off"
                                            style="flex: 1;">
                                        <button type="button" id="openScannerBtn" class="btn btn-primary scan-btn">
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="16"
                                                height="16">
                                                <path fill-rule="evenodd"
                                                    d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Scan QR
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Updating Form --}}
                        <div id="updatingForm" class="category-form"
                            style="display: {{ old('category', session('last_category')) == 'Updating' ? 'block' : 'none' }};">
                            <div style="border-left: 4px solid #2563eb; padding-left: 15px; margin-bottom: 20px;">
                                <h4 style="margin: 0 0 5px 0; font-size: 16px;">Updating Details</h4>
                                <p style="margin: 0; color: #6b7280; font-size: 13px;">Please fill in the required
                                    information below</p>
                            </div>

                            <div class="form-group" style="margin-top: 15px; margin-bottom: 20px;">
                                <label>Priority Type <span style="color: #dc2626;">*</span></label>
                                <div class="priority-buttons">
                                    <input type="hidden" name="priority_type_update" id="priority_type_update"
                                        value="{{ old('priority_type_update', session('last_priority_update', 'regular')) }}"
                                        required>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'senior' ? 'active' : '' }}"
                                        data-priority="senior"
                                        onclick="selectPriorityType('update', 'senior')">Senior</button>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'infant' ? 'active' : '' }}"
                                        data-priority="infant"
                                        onclick="selectPriorityType('update', 'infant')">Infant</button>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'pwd' ? 'active' : '' }}"
                                        data-priority="pwd" onclick="selectPriorityType('update', 'pwd')">PWD</button>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'pregnant' ? 'active' : '' }}"
                                        data-priority="pregnant"
                                        onclick="selectPriorityType('update', 'pregnant')">Pregnant</button>
                                    <button type="button"
                                        class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'regular' ? 'active' : '' }}"
                                        data-priority="regular"
                                        onclick="selectPriorityType('update', 'regular')">Regular</button>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fname_update">First Name <span
                                            style="color: #dc2626;">*</span></label>
                                    <input type="text" name="fname_update" id="fname_update"
                                        value="{{ old('fname_update') }}" pattern="[A-Za-zÑñ\s\-']+"
                                        placeholder="Enter first name" required>
                                </div>
                                <div class="form-group">
                                    <label for="mname_update">Middle Name</label>
                                    <input type="text" name="mname_update" id="mname_update"
                                        value="{{ old('mname_update') }}" pattern="[A-Za-zÑñ\s\-']*"
                                        placeholder="Enter middle name">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="lname_update">Last Name <span style="color: #dc2626;">*</span></label>
                                    <input type="text" name="lname_update" id="lname_update"
                                        value="{{ old('lname_update') }}" pattern="[A-Za-zÑñ\s\-']+"
                                        placeholder="Enter last name" required>
                                </div>
                                <div class="form-group">
                                    <label for="suffix_update">Suffix</label>
                                    <input type="text" name="suffix_update" id="suffix_update"
                                        value="{{ old('suffix_update') }}" pattern="[A-Za-zÑñ\s\-'.]*"
                                        placeholder="Jr., Sr., III">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="age_category_update">Age Category <span
                                            style="color: #dc2626;">*</span></label>
                                    <div class="age-category-buttons">
                                        <input type="hidden" name="age_category_update" id="age_category_update"
                                            value="{{ old('age_category_update') }}" required>
                                        <button type="button"
                                            class="age-btn {{ old('age_category_update') == '0-4 years old' ? 'active' : '' }}"
                                            data-age="0-4 years old"
                                            onclick="selectAge('update', '0-4 years old')">0-4 years old</button>
                                        <button type="button"
                                            class="age-btn {{ old('age_category_update') == '5 years old and above' ? 'active' : '' }}"
                                            data-age="5 years old and above"
                                            onclick="selectAge('update', '5 years old and above')">5 years old and
                                            above</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="birthdate_update">Birthdate <span
                                            style="color: #dc2626;">*</span></label>
                                    <input type="date" name="birthdate_update" id="birthdate_update"
                                        value="{{ old('birthdate_update') }}" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="PCN">PhilSys Card Number (PCN)</label>
                                    <input type="text" name="PCN" id="PCN" value="{{ old('PCN') }}"
                                        placeholder="Enter PCN">
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary" id="submitBtn">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Issue Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Today's Appointments Card --}}
            <div class="card">
                <div class="card-header">
                    <h3>
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path
                                d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                        Today's Appointments
                    </h3>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #2563eb, #1d4ed8)">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                    <path
                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                </svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Total Queue Today</span>
                                <span class="stat-value" id="totalQueue">{{ $queueCount ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706)">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Pending</span>
                                <span class="stat-value pending" id="pendingCount">{{ $pendingCount ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669)">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Completed</span>
                                <span class="stat-value completed"
                                    id="completedCount">{{ $completedCount ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="service-tabs">
                    <button class="service-tab active" data-service="all">All Services</button>
                    <button class="service-tab" data-service="NID Registration">NID Registration</button>
                    <button class="service-tab" data-service="Status Inquiry">Status Inquiry</button>
                    <button class="service-tab" data-service="Updating">NID Updating</button>
                </div>

                <div class="card-body">
                    <div class="search-box">
                        <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                        <input type="text" id="searchAppointments"
                            placeholder="Search by name or queue number...">
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Queue #</th>
                                    <th>Name</th>
                                    <th>Priority</th>
                                    <th>Service</th>
                                    <th>Time</th>
                                    <th>Action</th>
                            </thead>
                            <tbody id="appointmentsTableBody">
                                @forelse($appointments as $appointment)
                                    @php
                                        $createdTime = \Carbon\Carbon::parse($appointment->date)->setTimezone(
                                            'Asia/Manila',
                                        );
                                        $status = $appointment->status ?? 'pending';
                                        $showInTable =
                                            in_array($status, ['pending', 'serving']) ||
                                            (!$appointment->time_catered &&
                                                !in_array($status, ['completed', 'cancelled', 'no_show']));
                                        $priorityType = $appointment->priority_type ?? 'regular';
                                        $priorityDisplay = ucfirst($priorityType);
                                        $fullName = $appointment->lname . ', ' . $appointment->fname;
                                        if ($appointment->mname && trim($appointment->mname) !== '') {
                                            $fullName .= ' ' . $appointment->mname;
                                        }
                                        if ($appointment->suffix && trim($appointment->suffix) !== '') {
                                            $fullName .= ' ' . $appointment->suffix;
                                        }
                                    @endphp
                                    @if ($showInTable)
                                        <tr data-search="{{ strtolower($appointment->lname . ' ' . $appointment->fname) }}"
                                            data-id="{{ $appointment->n_id }}" data-queue="{{ $appointment->q_id }}"
                                            data-name="{{ $fullName }}"
                                            data-service="{{ $appointment->queue_for }}"
                                            data-time="{{ $createdTime->format('h:i A') }}">
                                            <td><span class="queue-number">{{ $appointment->q_id }}</span></td>
                                            <td>
                                                <div class="client-name">{{ $fullName }}</div>
                                            </td>
                                            <td><span
                                                    class="priority-badge priority-{{ $priorityType }}">{{ strtoupper($priorityDisplay) }}</span>
                                            </td>
                                            <td>{{ $appointment->queue_for }}</td>
                                            <td>{{ $createdTime->format('h:i A') }}</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <!-- Print Button (always visible) -->
                                                    <button type="button" class="btn-action print-slip-btn"
                                                        onclick="printAppointmentSlip(this)"
                                                        data-id="{{ $appointment->n_id }}"
                                                        data-queue="{{ $appointment->q_id }}"
                                                        data-name="{{ $fullName }}"
                                                        data-service="{{ $appointment->queue_for }}"
                                                        data-time="{{ $createdTime->format('h:i A') }}">
                                                        <svg viewBox="0 0 20 20" fill="currentColor" width="14"
                                                            height="14">
                                                            <path fill-rule="evenodd"
                                                                d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8h-2v2h2v-2zm-4-2a1 1 0 100-2 1 1 0 000 2z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Print
                                                    </button>

                                                    <!-- Three Dots Dropdown Menu -->
                                                    <div class="dropdown">
                                                        <button type="button" class="dropdown-btn"
                                                            onclick="toggleDropdown(event, this)">
                                                            <svg viewBox="0 0 20 20" fill="currentColor"
                                                                width="16" height="16">
                                                                <path
                                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
                                                            </svg>
                                                        </button>
                                                        <div class="dropdown-content">
                                                            <button type="button" class="dropdown-item edit-item"
                                                                data-id="{{ $appointment->n_id }}"
                                                                data-queue="{{ $appointment->q_id }}"
                                                                data-name="{{ $fullName }}"
                                                                data-fname="{{ $appointment->fname }}"
                                                                data-mname="{{ $appointment->mname }}"
                                                                data-lname="{{ $appointment->lname }}"
                                                                data-suffix="{{ $appointment->suffix }}"
                                                                data-priority="{{ $appointment->priority_type }}"
                                                                data-service="{{ $appointment->queue_for }}"
                                                                data-birthdate="{{ $appointment->birthdate }}"
                                                                data-age-category="{{ $appointment->age_category }}"
                                                                data-status="{{ $appointment->status }}"
                                                                data-pcn="{{ $appointment->PCN ?? '' }}"
                                                                data-trn="{{ $appointment->trn ?? '' }}"
                                                                onclick="openEditModal(this)">
                                                                <svg viewBox="0 0 20 20" fill="currentColor"
                                                                    width="14" height="14">
                                                                    <path
                                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-8 8a2 2 0 01-1.414.586H4a1 1 0 01-1-1v-3a1 1 0 01.293-.707l8-8z" />
                                                                </svg>
                                                                Edit
                                                            </button>
                                                            <button type="button" class="dropdown-item cancel-item"
                                                                data-id="{{ $appointment->n_id }}"
                                                                data-name="{{ $fullName }}"
                                                                data-queue="{{ $appointment->q_id }}"
                                                                onclick="cancelAppointment(this)">
                                                                <svg viewBox="0 0 20 20" fill="currentColor"
                                                                    width="14" height="14">
                                                                    <path fill-rule="evenodd"
                                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                                Cancel
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="6" class="empty-state">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <p>No pending appointments for today</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Appointment Modal - PHP/Modal based with 2 columns --}}
        <div id="editAppointmentModal" class="edit-modal" style="display: none;">
            <div class="edit-modal-overlay"></div>
            <div class="edit-modal-container">
                <div class="edit-modal-header">
                    <h3 class="edit-modal-title">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path
                                d="M13.586 3.586a2 2 0 112.828 2.828l-8 8a2 2 0 01-1.414.586H4a1 1 0 01-1-1v-3a1 1 0 01.293-.707l8-8z" />
                        </svg>
                        Edit Appointment
                    </h3>
                    <button type="button" class="edit-modal-close" onclick="closeEditModal()">&times;</button>
                </div>
                <form id="editAppointmentForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="edit_id" id="edit_id">

                    <div class="edit-modal-body">
                        <div class="edit-two-columns">
                            <!-- Left Column -->
                            <div class="edit-column">
                                <div class="edit-form-group">
                                    <label for="edit_queue">Queue Number</label>
                                    <input type="text" id="edit_queue" class="edit-form-control" readonly
                                        style="background:#f3f4f6;">
                                </div>
                                <div class="edit-form-group">
                                    <label for="edit_fname">First Name <span class="text-danger">*</span></label>
                                    <input type="text" id="edit_fname" name="fname" class="edit-form-control"
                                        required>
                                </div>
                                <div class="edit-form-group">
                                    <label for="edit_mname">Middle Name</label>
                                    <input type="text" id="edit_mname" name="mname" class="edit-form-control">
                                </div>
                                <div class="edit-form-group">
                                    <label for="edit_lname">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" id="edit_lname" name="lname" class="edit-form-control"
                                        required>
                                </div>
                                <div class="edit-form-group">
                                    <label for="edit_suffix">Suffix</label>
                                    <input type="text" id="edit_suffix" name="suffix" class="edit-form-control"
                                        placeholder="Jr., Sr., III">
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="edit-column">
                                <div class="edit-form-group">
                                    <label for="edit_priority">Priority Type <span
                                            class="text-danger">*</span></label>
                                    <select id="edit_priority" name="priority_type" class="edit-form-control"
                                        required>
                                        <option value="regular">Regular</option>
                                        <option value="senior">Senior</option>
                                        <option value="infant">Infant</option>
                                        <option value="pwd">PWD</option>
                                        <option value="pregnant">Pregnant</option>
                                    </select>
                                </div>
                                <div class="edit-form-group">
                                    <label for="edit_birthdate">Birthdate <span class="text-danger">*</span></label>
                                    <input type="date" id="edit_birthdate" name="birthdate"
                                        class="edit-form-control" required>
                                </div>
                                <div class="edit-form-group">
                                    <label for="edit_age_category">Age Category</label>
                                    <select id="edit_age_category" name="age_category" class="edit-form-control">
                                        <option value="">Select Age Category</option>
                                        <option value="0-4 years old">0-4 years old</option>
                                        <option value="5 years old and above">5 years old and above</option>
                                    </select>
                                </div>
                                <div class="edit-form-group" id="edit_pcn_group" style="display: none;">
                                    <label for="edit_pcn">PhilSys Card Number (PCN)</label>
                                    <input type="text" id="edit_pcn" name="PCN" class="edit-form-control"
                                        placeholder="Enter PCN">
                                </div>
                                <div class="edit-form-group" id="edit_trn_group" style="display: none;">
                                    <label for="edit_trn">Transaction Reference Number (TRN)</label>
                                    <input type="text" id="edit_trn" name="trn" class="edit-form-control"
                                        placeholder="Enter TRN">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="edit-modal-footer">
                        <button type="button" class="edit-btn-cancel" onclick="closeEditModal()">Cancel</button>
                        <button type="submit" class="edit-btn-save">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- QR Scanner Modal --}}
        <div id="scannerModal" class="scanner-modal" style="display: none;">
            <div class="scanner-modal-content">
                <div class="scanner-modal-header">
                    <h3 style="margin: 0; color: #2563eb;">Scan QR Code</h3>
                    <button type="button" id="closeModalBtn" class="scanner-modal-close">×</button>
                </div>
                <div class="scanner-modal-body">
                    <div id="qr-reader" style="width: 100%; min-height: 400px;"></div>
                </div>
                <div class="scanner-modal-footer">
                    <button type="button" id="cancelScannerBtn" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js"></script>
<script>
    let html5QrcodeScanner = null;
    let isScanning = false;
    let currentSearchTerm = '';
    let refreshInterval;
    let formSubmitted = false;
    let lastAppointmentsData = null;
    let lastStatsData = null;

    // Update the openEditModal function - change this line:
    function openEditModal(button) {
        const appointment = {
            id: button.getAttribute('data-id'),
            queue: button.getAttribute('data-queue'),
            fname: button.getAttribute('data-fname'),
            mname: button.getAttribute('data-mname'),
            lname: button.getAttribute('data-lname'),
            suffix: button.getAttribute('data-suffix'),
            priority: button.getAttribute('data-priority'),
            service: button.getAttribute('data-service'),
            birthdate: button.getAttribute('data-birthdate'),
            ageCategory: button.getAttribute('data-age-category'),
            pcn: button.getAttribute('data-pcn') || '',
            trn: button.getAttribute('data-trn') || ''
        };

        // Populate modal fields
        document.getElementById('edit_id').value = appointment.id;
        document.getElementById('edit_queue').value = appointment.queue;
        document.getElementById('edit_fname').value = appointment.fname;
        document.getElementById('edit_mname').value = appointment.mname || '';
        document.getElementById('edit_lname').value = appointment.lname;
        document.getElementById('edit_suffix').value = appointment.suffix || '';
        document.getElementById('edit_priority').value = appointment.priority || 'regular';

        // Fix the birthdate format - handle invalid dates
        let birthdateValue = appointment.birthdate;
        if (birthdateValue && birthdateValue.includes(' ')) {
            birthdateValue = birthdateValue.split(' ')[0];
        }
        if (birthdateValue && birthdateValue !== '0000-00-00' && !birthdateValue.includes('00:00:00')) {
            document.getElementById('edit_birthdate').value = birthdateValue;
        } else {
            document.getElementById('edit_birthdate').value = '';
        }

        document.getElementById('edit_age_category').value = appointment.ageCategory || '';

        // Handle service-specific fields
        const pcnGroup = document.getElementById('edit_pcn_group');
        const trnGroup = document.getElementById('edit_trn_group');

        if (appointment.service === 'Updating') {
            pcnGroup.style.display = 'flex';
            trnGroup.style.display = 'none';
            document.getElementById('edit_pcn').value = appointment.pcn;
        } else if (appointment.service === 'Status Inquiry') {
            pcnGroup.style.display = 'none';
            trnGroup.style.display = 'flex';
            document.getElementById('edit_trn').value = appointment.trn;
        } else {
            pcnGroup.style.display = 'none';
            trnGroup.style.display = 'none';
        }

        // ========== FIXED: Use the correct route URL with screener prefix ==========
        const form = document.getElementById('editAppointmentForm');
        form.action = `/screener/appointment/update/${appointment.id}`;
        // =========================================================================

        // Show modal
        document.getElementById('editAppointmentModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editAppointmentModal').style.display = 'none';
        document.getElementById('editAppointmentForm').reset();
    }

    // Close modal when clicking overlay
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('editAppointmentModal');
        if (e.target.classList.contains('edit-modal-overlay')) {
            closeEditModal();
        }
    });

    // Handle edit form submission via AJAX with SweetAlert
    document.getElementById('editAppointmentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const actionUrl = form.action;

        Swal.fire({
            title: 'Saving Changes...',
            text: 'Please wait while we update the appointment.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Appointment updated successfully',
                        icon: 'success',
                        confirmButtonColor: '#2563eb',
                        timer: 2000
                    });
                    closeEditModal();
                    fetchAppointments(); // Refresh the table
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to update appointment',
                        icon: 'error',
                        confirmButtonColor: '#dc2626'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred',
                    icon: 'error',
                    confirmButtonColor: '#dc2626'
                });
            });
    });

    // ========== PREVENT DUPLICATE SUBMISSIONS ==========
    let isSubmitting = false;

    // ========== CUSTOM FORM VALIDATION ==========
    function validateCurrentForm() {
        const category = document.getElementById('selectedCategory').value;
        let isValid = true;
        let errorMessage = '';

        if (category === 'NID Registration') {
            const fname = document.getElementById('fname_nid').value.trim();
            const lname = document.getElementById('lname_nid').value.trim();
            const birthdate = document.getElementById('birthdate_nid').value;
            const ageCategory = document.getElementById('age_category_nid').value;
            const priorityType = document.getElementById('priority_type_nid').value;

            if (!fname) {
                isValid = false;
                errorMessage = 'First Name is required';
                document.getElementById('fname_nid').focus();
            } else if (!lname) {
                isValid = false;
                errorMessage = 'Last Name is required';
                document.getElementById('lname_nid').focus();
            } else if (!birthdate) {
                isValid = false;
                errorMessage = 'Birthdate is required';
                document.getElementById('birthdate_nid').focus();
            } else if (!ageCategory) {
                isValid = false;
                errorMessage = 'Age Category is required';
            } else if (!priorityType) {
                isValid = false;
                errorMessage = 'Priority Type is required';
            }
        } else if (category === 'Status Inquiry') {
            const fname = document.getElementById('fname_status').value.trim();
            const lname = document.getElementById('lname_status').value.trim();
            const birthdate = document.getElementById('birthdate_status').value;
            const ageCategory = document.getElementById('age_category_status').value;
            const priorityType = document.getElementById('priority_type_status').value;

            if (!fname) {
                isValid = false;
                errorMessage = 'First Name is required';
                document.getElementById('fname_status').focus();
            } else if (!lname) {
                isValid = false;
                errorMessage = 'Last Name is required';
                document.getElementById('lname_status').focus();
            } else if (!birthdate) {
                isValid = false;
                errorMessage = 'Birthdate is required';
                document.getElementById('birthdate_status').focus();
            } else if (!ageCategory) {
                isValid = false;
                errorMessage = 'Age Category is required';
            } else if (!priorityType) {
                isValid = false;
                errorMessage = 'Priority Type is required';
            }
        } else if (category === 'Updating') {
            const fname = document.getElementById('fname_update').value.trim();
            const lname = document.getElementById('lname_update').value.trim();
            const birthdate = document.getElementById('birthdate_update').value;
            const ageCategory = document.getElementById('age_category_update').value;
            const priorityType = document.getElementById('priority_type_update').value;

            if (!fname) {
                isValid = false;
                errorMessage = 'First Name is required';
                document.getElementById('fname_update').focus();
            } else if (!lname) {
                isValid = false;
                errorMessage = 'Last Name is required';
                document.getElementById('lname_update').focus();
            } else if (!birthdate) {
                isValid = false;
                errorMessage = 'Birthdate is required';
                document.getElementById('birthdate_update').focus();
            } else if (!ageCategory) {
                isValid = false;
                errorMessage = 'Age Category is required';
            } else if (!priorityType) {
                isValid = false;
                errorMessage = 'Priority Type is required';
            }
        }

        if (!isValid) {
            Swal.fire({
                title: 'Validation Error',
                text: errorMessage,
                icon: 'warning',
                confirmButtonColor: '#2563eb'
            });
        }

        return isValid;
    }

    // ========== DROPDOWN MENU FUNCTIONS ==========
    function toggleDropdown(event, button) {
        event.stopPropagation();
        const dropdown = button.closest('.dropdown');
        const content = dropdown.querySelector('.dropdown-content');
        const allDropdowns = document.querySelectorAll('.dropdown-content');

        allDropdowns.forEach(drop => {
            if (drop !== content) {
                drop.classList.remove('show');
            }
        });

        content.classList.toggle('show');

        let backdrop = document.querySelector('.dropdown-backdrop');
        if (!backdrop) {
            backdrop = document.createElement('div');
            backdrop.className = 'dropdown-backdrop';
            document.body.appendChild(backdrop);

            backdrop.addEventListener('click', function() {
                document.querySelectorAll('.dropdown-content').forEach(drop => {
                    drop.classList.remove('show');
                });
                backdrop.classList.remove('show');
            });
        }

        if (content.classList.contains('show')) {
            backdrop.classList.add('show');
        } else {
            backdrop.classList.remove('show');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.dropdown-content').forEach(drop => {
                drop.classList.remove('show');
            });
            const backdrop = document.querySelector('.dropdown-backdrop');
            if (backdrop) backdrop.classList.remove('show');
            closeEditModal();
        }
    });

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-content').forEach(drop => {
                drop.classList.remove('show');
            });
            const backdrop = document.querySelector('.dropdown-backdrop');
            if (backdrop) backdrop.classList.remove('show');
        }
    });

    // ========== PRINT SLIP FUNCTION ==========
    function printAppointmentSlip(button) {
        const queueNumber = button.getAttribute('data-queue');
        const clientName = button.getAttribute('data-name');
        const service = button.getAttribute('data-service');
        const time = button.getAttribute('data-time');
        const currentDate = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const printContent = `
            <div style="text-align: center; font-family: 'Courier New', monospace; padding: 20px;">
                <h1 style="font-size: 16pt; margin: 0 0 10px 0;">PHILIPPINE STATISTICS AUTHORITY</h1>
                <div style="font-size: 10pt; margin-bottom: 5px;">Appointment Slip</div>
                <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
                <div style="font-size: 10pt; margin-bottom: 5px;"><strong>Name:</strong> ${clientName}</div>
                <div style="font-size: 10pt; margin-bottom: 5px;"><strong>Service:</strong> ${service}</div>
                <div style="font-size: 10pt; margin-bottom: 15px;"><strong>Date:</strong> ${currentDate}</div>
                <div style="font-size: 10pt; margin-bottom: 15px;"><strong>Time Issued:</strong> ${time}</div>
                <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
                <div style="font-size: 28pt; font-weight: bold; letter-spacing: 4px; margin: 20px 0;">
                    ${queueNumber}
                </div>
                <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
                <div style="font-size: 8pt; margin-top: 10px;">Please present this slip when called</div>
                <div style="font-size: 8pt;">Thank you for choosing PSA!</div>
            </div>
        `;

        const printWindow = window.open('', '_blank', 'width=400,height=500');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Appointment Slip - ${queueNumber}</title>
                    <style>
                        @media print {
                            @page { size: 80mm auto; margin: 0; }
                            body { margin: 0; padding: 10px; font-family: "Courier New", Courier, monospace; }
                        }
                        body { margin: 0; padding: 20px; }
                    </style>
                </head>
                <body>${printContent}</body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.onafterprint = () => printWindow.close();

        Swal.fire({
            title: 'Printing...',
            text: 'Appointment slip is being printed.',
            icon: 'info',
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }

    // ========== CHECK IF DATA HAS CHANGED ==========
    function hasDataChanged(newAppointments, newStats) {
        if (lastStatsData) {
            if (lastStatsData.total !== newStats.total ||
                lastStatsData.pending !== newStats.pending ||
                lastStatsData.completed !== newStats.completed) {
                return true;
            }
        }
        if (lastAppointmentsData && lastAppointmentsData.length !== newAppointments.length) {
            return true;
        }
        if (lastAppointmentsData && newAppointments) {
            for (let i = 0; i < newAppointments.length; i++) {
                const newApp = newAppointments[i];
                const oldApp = lastAppointmentsData.find(a => a.n_id === newApp.n_id);
                if (!oldApp) return true;
                if (oldApp.status !== newApp.status || oldApp.time_catered !== newApp.time_catered) {
                    return true;
                }
            }
        }
        return false;
    }

    function selectCategory(category) {
        document.getElementById('selectedCategory').value = category;
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.style.backgroundColor = 'white';
            btn.style.color = '#374151';
        });
        const selectedBtn = document.querySelector(`.category-btn[data-category="${category}"]`);
        if (selectedBtn) {
            selectedBtn.classList.add('active');
            selectedBtn.style.backgroundColor = '#2563eb';
            selectedBtn.style.color = 'white';
        }
        toggleForm(category);
        $.ajax({
            url: '{{ route('appointment.store-category') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                category: category
            }
        });
    }

    function selectAge(formType, ageValue) {
        const hiddenInput = document.getElementById(`age_category_${formType}`);
        if (hiddenInput) {
            hiddenInput.value = ageValue;
            const event = new Event('change', {
                bubbles: true
            });
            hiddenInput.dispatchEvent(event);
        }
        let formId = formType === 'nid' ? 'nidForm' : (formType === 'status' ? 'statusForm' : 'updatingForm');
        document.querySelectorAll(`#${formId} .age-btn`).forEach(btn => {
            btn.classList.remove('active');
            btn.style.backgroundColor = 'white';
            btn.style.color = '#374151';
        });
        const selectedBtn = document.querySelector(`#${formId} .age-btn[data-age="${ageValue}"]`);
        if (selectedBtn) {
            selectedBtn.classList.add('active');
            selectedBtn.style.backgroundColor = '#10B981';
            selectedBtn.style.color = 'white';
        }
    }

    function selectPriorityType(formType, priorityValue) {
        const hiddenInput = document.getElementById(`priority_type_${formType}`);
        if (hiddenInput) {
            hiddenInput.value = priorityValue;
            const event = new Event('change', {
                bubbles: true
            });
            hiddenInput.dispatchEvent(event);
        }
        let formId = formType === 'nid' ? 'nidForm' : (formType === 'status' ? 'statusForm' : 'updatingForm');
        document.querySelectorAll(`#${formId} .priority-btn`).forEach(btn => {
            btn.classList.remove('active');
            btn.style.backgroundColor = 'white';
            btn.style.color = '#374151';
        });
        const selectedBtn = document.querySelector(`#${formId} .priority-btn[data-priority="${priorityValue}"]`);
        if (selectedBtn) {
            selectedBtn.classList.add('active');
            selectedBtn.style.backgroundColor = '#2563eb';
            selectedBtn.style.color = 'white';
        }
        if (priorityValue === 'infant') selectAge(formType, '0-4 years old');
        else if (priorityValue !== 'infant' && priorityValue !== 'regular') {
            selectAge(formType, '5 years old and above');
        }
        $.ajax({
            url: '{{ route('appointment.store-priority') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                priority_type: priorityValue,
                form_type: formType
            }
        });
    }

    function toggleForm(category) {
        if (!category) category = document.getElementById('selectedCategory').value;
        document.getElementById('nidForm').style.display = 'none';
        document.getElementById('statusForm').style.display = 'none';
        document.getElementById('updatingForm').style.display = 'none';
        if (category === 'NID Registration') document.getElementById('nidForm').style.display = 'block';
        else if (category === 'Status Inquiry') document.getElementById('statusForm').style.display = 'block';
        else if (category === 'Updating') document.getElementById('updatingForm').style.display = 'block';
    }

    async function startQRScanner() {
        const trnInput = document.getElementById('trn');
        try {
            if (typeof Html5Qrcode === 'undefined') throw new Error('QR Scanner library not loaded');
            html5QrcodeScanner = new Html5Qrcode("qr-reader");
            const qrCodeSuccessCallback = (decodedText) => {
                trnInput.value = decodedText;
                closeScannerModal();
                Swal.fire({
                    title: 'Success!',
                    text: 'QR Code scanned successfully!',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true
                });
            };
            const cameras = await Html5Qrcode.getCameras();
            let selectedCameraId = cameras[0]?.id;
            for (const camera of cameras) {
                if (camera.label.toLowerCase().includes('back') || camera.label.includes('environment')) {
                    selectedCameraId = camera.id;
                    break;
                }
            }
            await html5QrcodeScanner.start({
                deviceId: selectedCameraId
            }, {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            }, qrCodeSuccessCallback);
            isScanning = true;
        } catch (err) {
            console.error('Scanner error:', err);
            Swal.fire({
                title: 'Error',
                text: 'Unable to access camera',
                icon: 'error'
            });
        }
    }

    function showScannerModal() {
        document.getElementById('scannerModal').style.display = 'flex';
        setTimeout(() => startQRScanner(), 200);
    }

    function closeScannerModal() {
        document.getElementById('scannerModal').style.display = 'none';
        if (html5QrcodeScanner && isScanning) html5QrcodeScanner.stop().then(() => {
            isScanning = false;
        });
    }

    function cancelAppointment(button) {
        const appointmentId = button.getAttribute('data-id');
        const clientName = button.getAttribute('data-name');
        const queueNumber = button.getAttribute('data-queue');
        const row = button.closest('tr');

        const dropdown = button.closest('.dropdown');
        if (dropdown) {
            dropdown.querySelector('.dropdown-content')?.classList.remove('show');
        }

        Swal.fire({
            title: 'Cancel Appointment?',
            html: `Cancel <strong>${queueNumber}</strong> for <strong>${clientName}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Yes, Cancel',
            preConfirm: () => fetch(`/appointment/update-status/${appointmentId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: 'cancelled'
                })
            }).then(res => res.json())
        }).then(result => {
            if (result.isConfirmed && result.value.success) {
                row.remove();
                if (document.querySelector('#appointmentsTableBody tr:not(.empty-state)') === null) {
                    document.getElementById('appointmentsTableBody').innerHTML =
                        '<tr><td colspan="6" class="empty-state"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg><p>No pending appointments for today</p></td></tr>';
                }
                Swal.fire({
                    title: 'Cancelled!',
                    text: `${queueNumber} cancelled`,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                fetchAppointments();
            } else if (result.isConfirmed && !result.value.success) {
                Swal.fire('Error!', result.value.message || 'Failed to cancel appointment', 'error');
            }
        });
    }

    function updateAppointmentsTable(appointments) {
        const tbody = document.getElementById('appointmentsTableBody');
        if (!appointments || appointments.length === 0) {
            tbody.innerHTML =
                '<tr><td colspan="6" class="empty-state"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg><p>No pending appointments for today</p></td></tr>';
            return;
        }
        let html = '';
        appointments.forEach(app => {
            const status = app.status || 'pending';
            const showInTable = ['pending', 'serving'].includes(status) || (!app.time_catered && !['completed',
                'cancelled', 'no_show'
            ].includes(status));
            if (showInTable) {
                const createdTime = new Date(app.date).toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true,
                    timeZone: 'Asia/Manila'
                });
                let fullName = app.lname + ', ' + app.fname;
                if (app.mname && app.mname.trim() !== '') fullName += ' ' + app.mname;
                if (app.suffix && app.suffix.trim() !== '') fullName += ' ' + app.suffix;
                const priorityType = app.priority_type?.toLowerCase() || 'regular';
                const priorityDisplay = priorityType.charAt(0).toUpperCase() + priorityType.slice(1);
                html += `<tr data-search="${(app.lname + ' ' + app.fname).toLowerCase()}" data-id="${app.n_id}" data-queue="${app.q_id}" data-name="${fullName}" data-service="${app.queue_for}" data-time="${createdTime}">
                    <td><span class="queue-number">${app.q_id}</span></td>
                    <td><div class="client-name">${fullName}</div></td>
                    <td><span class="priority-badge priority-${priorityType}">${priorityDisplay.toUpperCase()}</span></td>
                    <td>${app.queue_for}</td>
                    <td>${createdTime}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action print-slip-btn" onclick="printAppointmentSlip(this)" data-id="${app.n_id}" data-queue="${app.q_id}" data-name="${fullName}" data-service="${app.queue_for}" data-time="${createdTime}">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8h-2v2h2v-2zm-4-2a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                                Print
                            </button>
                            <div class="dropdown">
                                <button type="button" class="dropdown-btn" onclick="toggleDropdown(event, this)">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                </button>
                                <div class="dropdown-content">
                                    <button type="button" class="dropdown-item edit-item"
                                        data-id="${app.n_id}" data-queue="${app.q_id}" data-name="${fullName}"
                                        data-fname="${app.fname}" data-mname="${app.mname || ''}" data-lname="${app.lname}"
                                        data-suffix="${app.suffix || ''}" data-priority="${app.priority_type}"
                                        data-service="${app.queue_for}" data-birthdate="${app.birthdate}"
                                        data-age-category="${app.age_category}" data-status="${app.status}"
                                        data-pcn="${app.PCN || ''}" data-trn="${app.trn || ''}"
                                        onclick="openEditModal(this)">
                                        <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14"><path d="M13.586 3.586a2 2 0 112.828 2.828l-8 8a2 2 0 01-1.414.586H4a1 1 0 01-1-1v-3a1 1 0 01.293-.707l8-8z" /></svg>
                                        Edit
                                    </button>
                                    <button type="button" class="dropdown-item cancel-item"
                                        data-id="${app.n_id}" data-name="${fullName}" data-queue="${app.q_id}"
                                        onclick="cancelAppointment(this)">
                                        <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                     </td>
                 </tr>`;
            }
        });
        tbody.innerHTML = html || '<tr><td colspan="6" class="empty-state"><p>No pending appointments</p></td></tr>';
        if (currentSearchTerm) filterTableRows();
    }

    function filterTableRows() {
        const rows = document.querySelectorAll('#appointmentsTableBody tr');
        rows.forEach(row => {
            if (row.classList.contains('empty-state')) return;
            const searchData = row.getAttribute('data-search') || row.textContent.toLowerCase();
            row.style.display = searchData.includes(currentSearchTerm) ? '' : 'none';
        });
    }

    function fetchAppointments() {
        fetch('/screener/appointment/today?_=' + new Date().getTime(), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => response.json()).then(data => {
            if (data.success) {
                const newStats = data.stats;
                const newAppointments = data.appointments;
                if (hasDataChanged(newAppointments, newStats)) {
                    console.log('Data changed - updating UI at', new Date().toLocaleTimeString());
                    document.getElementById('totalQueue').textContent = newStats.total;
                    document.getElementById('pendingCount').textContent = newStats.pending;
                    document.getElementById('completedCount').textContent = newStats.completed;
                    updateAppointmentsTable(newAppointments);
                    lastAppointmentsData = JSON.parse(JSON.stringify(newAppointments));
                    lastStatsData = {
                        ...newStats
                    };
                }
            }
        }).catch(error => console.error('Error fetching appointments:', error));
    }

    function startSmartAutoRefresh() {
        console.log('Smart auto-refresh started');
        fetchAppointments();
        refreshInterval = setInterval(fetchAppointments, 5000);
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) fetchAppointments();
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const savedCategory = document.getElementById('selectedCategory').value;
        selectCategory(savedCategory);

        const appointmentForm = document.getElementById('appointmentForm');
        const submitBtn = document.getElementById('submitBtn');

        if (appointmentForm) {
            appointmentForm.addEventListener('submit', function(e) {
                if (!validateCurrentForm()) {
                    e.preventDefault();
                    return false;
                }
                if (isSubmitting) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Please Wait',
                        text: 'Processing...',
                        icon: 'info',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return false;
                }
                isSubmitting = true;
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<svg class="animate-spin" viewBox="0 0 20 20" fill="currentColor" width="20" height="20" style="animation: spin 1s linear infinite;"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" /></svg> Processing...';
                submitBtn.style.opacity = '0.7';
                submitBtn.style.cursor = 'not-allowed';
            });
        }

        document.getElementById('openScannerBtn')?.addEventListener('click', () => {
            if (document.getElementById('selectedCategory').value !== 'Status Inquiry') {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Please select Status Inquiry category first.',
                    icon: 'warning'
                });
                selectCategory('Status Inquiry');
                return;
            }
            showScannerModal();
        });
        document.getElementById('closeModalBtn')?.addEventListener('click', closeScannerModal);
        document.getElementById('cancelScannerBtn')?.addEventListener('click', closeScannerModal);
        document.querySelectorAll('.service-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.service-tab').forEach(t => t.classList.remove(
                    'active'));
                this.classList.add('active');
                const service = this.dataset.service;
                const rows = document.querySelectorAll('#appointmentsTableBody tr');
                rows.forEach(row => {
                    if (row.classList.contains('empty-state')) return;
                    const serviceCell = row.querySelector('td:nth-child(4)')
                        ?.textContent;
                    row.style.display = (service === 'all' || serviceCell === service) ?
                        '' : 'none';
                });
            });
        });
        document.getElementById('searchAppointments')?.addEventListener('keyup', function() {
            currentSearchTerm = this.value.toLowerCase();
            filterTableRows();
        });

        startSmartAutoRefresh();

        @if (session('printSlip'))
            setTimeout(function() {
                const queueNumber = '{{ session('printSlip')['queueNumber'] }}';
                const clientName = '{{ session('printSlip')['name'] }}';
                const service = '{{ session('printSlip')['service'] }}';
                const dateTime = '{{ session('printSlip')['dateTime'] }}';
                const printContent =
                    `<div style="text-align: center; font-family: 'Courier New', monospace; padding: 20px;"><h1 style="font-size: 16pt;">PHILIPPINE STATISTICS AUTHORITY</h1><div>Appointment Slip</div><div style="border-top:1px dashed #000;margin:10px 0;"></div><div><strong>Name:</strong> ${clientName}</div><div><strong>Service:</strong> ${service}</div><div><strong>Date & Time:</strong> ${dateTime}</div><div style="border-top:1px dashed #000;margin:10px 0;"></div><div style="font-size:28pt;font-weight:bold;">${queueNumber}</div><div style="border-top:1px dashed #000;margin:10px 0;"></div><div>Please present this slip when called</div><div>Thank you for choosing PSA!</div></div>`;
                const printWindow = window.open('', '_blank', 'width=400,height=500');
                printWindow.document.write(
                    `<html><head><title>Appointment Slip - ${queueNumber}</title><style>@media print{@page{size:80mm auto;margin:0;}body{margin:0;padding:10px;}}</style></head><body>${printContent}</body></html>`
                );
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.onafterprint = () => printWindow.close();
            }, 500);
        @endif
    });
</script>
