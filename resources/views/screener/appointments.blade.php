{{-- resources/views/screener/appointments.blade.php --}}
<x-header title="Appointments" />

<div class="app-container">
    <x-screener.sidebar />
    
    <style>
        

       
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

      
    </style>

    <main class="main-content">
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
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #1f2937; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em;">
                            Select Category:
                        </label>
                        <div style="display: flex; gap: 12px; margin-top: 5px;">
                            <button type="button"
                                class="category-btn {{ old('category', session('last_category')) == 'NID Registration' ? 'active' : '' }}"
                                data-category="NID Registration" onclick="selectCategory('NID Registration')">
                                <span style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
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

                    <form method="POST" action="{{ route('appointment.issue') }}" id="appointmentForm" accept-charset="UTF-8">
                        @csrf
                        <input type="hidden" name="category" id="selectedCategory" value="{{ old('category', session('last_category', 'NID Registration')) }}">

                        {{-- NID Registration Form --}}
                        <div id="nidForm" class="category-form" style="display: {{ old('category', session('last_category')) == 'NID Registration' ? 'block' : 'none' }};">
                            <div style="border-left: 4px solid #2563eb; padding-left: 15px; margin-bottom: 20px;">
                                <h4 style="margin: 0 0 5px 0; font-size: 16px;">NID Registration Details</h4>
                                <p style="margin: 0; color: #6b7280; font-size: 13px;">Please fill in the required information below</p>
                            </div>

                            <div class="form-group" style="margin-top: 15px; margin-bottom: 20px;">
                                <label>Priority Type <span style="color: #dc2626;">*</span></label>
                                <div class="priority-buttons">
                                    <input type="hidden" name="priority_type_nid" id="priority_type_nid" value="{{ old('priority_type_nid', session('last_priority_nid', 'regular')) }}">
                                    <button type="button" class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'senior' ? 'active' : '' }}" data-priority="senior" onclick="selectPriorityType('nid', 'senior')">Senior</button>
                                    <button type="button" class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'infant' ? 'active' : '' }}" data-priority="infant" onclick="selectPriorityType('nid', 'infant')">Infant</button>
                                    <button type="button" class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'pwd' ? 'active' : '' }}" data-priority="pwd" onclick="selectPriorityType('nid', 'pwd')">PWD</button>
                                    <button type="button" class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'pregnant' ? 'active' : '' }}" data-priority="pregnant" onclick="selectPriorityType('nid', 'pregnant')">Pregnant</button>
                                    <button type="button" class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'regular' ? 'active' : '' }}" data-priority="regular" onclick="selectPriorityType('nid', 'regular')">Regular</button>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fname_nid">First Name <span style="color: #dc2626;">*</span></label>
                                    <input type="text" name="fname_nid" id="fname_nid" value="{{ old('fname_nid') }}" pattern="[A-Za-zÑñ\s\-']+" title="Only letters, spaces, hyphens, and apostrophes are allowed" placeholder="Enter first name">
                                </div>
                                <div class="form-group">
                                    <label for="mname_nid">Middle Name</label>
                                    <input type="text" name="mname_nid" id="mname_nid" value="{{ old('mname_nid') }}" pattern="[A-Za-zÑñ\s\-']*" placeholder="Enter middle name">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="lname_nid">Last Name <span style="color: #dc2626;">*</span></label>
                                    <input type="text" name="lname_nid" id="lname_nid" value="{{ old('lname_nid') }}" pattern="[A-Za-zÑñ\s\-']+" placeholder="Enter last name">
                                </div>
                                <div class="form-group">
                                    <label for="suffix_nid">Suffix</label>
                                    <input type="text" name="suffix_nid" id="suffix_nid" value="{{ old('suffix_nid') }}" pattern="[A-Za-zÑñ\s\-'.]*" placeholder="Jr., Sr., III">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="age_category_nid">Age Category <span style="color: #dc2626;">*</span></label>
                                    <div class="age-category-buttons">
                                        <input type="hidden" name="age_category_nid" id="age_category_nid" value="{{ old('age_category_nid') }}">
                                        <button type="button" class="age-btn {{ old('age_category_nid') == '0-4 years old' ? 'active' : '' }}" data-age="0-4 years old" onclick="selectAge('nid', '0-4 years old')">0-4 years old</button>
                                        <button type="button" class="age-btn {{ old('age_category_nid') == '5 years old and above' ? 'active' : '' }}" data-age="5 years old and above" onclick="selectAge('nid', '5 years old and above')">5 years old and above</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="birthdate_nid">Birthdate <span style="color: #dc2626;">*</span></label>
                                    <input type="date" name="birthdate_nid" id="birthdate_nid" value="{{ old('birthdate_nid') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Status Inquiry Form --}}
                        <div id="statusForm" class="category-form" style="display: {{ old('category', session('last_category')) == 'Status Inquiry' ? 'block' : 'none' }};">
                            <div style="border-left: 4px solid #2563eb; padding-left: 15px; margin-bottom: 20px;">
                                <h4 style="margin: 0 0 5px 0; font-size: 16px;">Status Inquiry Details</h4>
                                <p style="margin: 0; color: #6b7280; font-size: 13px;">Please fill in the required information below</p>
                            </div>

                            <div class="form-group" style="margin-top: 15px; margin-bottom: 20px;">
                                <label>Priority Type <span style="color: #dc2626;">*</span></label>
                                <div class="priority-buttons">
                                    <input type="hidden" name="priority_type_status" id="priority_type_status" value="{{ old('priority_type_status', session('last_priority_status', 'regular')) }}">
                                    <button type="button" class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'senior' ? 'active' : '' }}" data-priority="senior" onclick="selectPriorityType('status', 'senior')">Senior</button>
                                    <button type="button" class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'infant' ? 'active' : '' }}" data-priority="infant" onclick="selectPriorityType('status', 'infant')">Infant</button>
                                    <button type="button" class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'pwd' ? 'active' : '' }}" data-priority="pwd" onclick="selectPriorityType('status', 'pwd')">PWD</button>
                                    <button type="button" class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'pregnant' ? 'active' : '' }}" data-priority="pregnant" onclick="selectPriorityType('status', 'pregnant')">Pregnant</button>
                                    <button type="button" class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'regular' ? 'active' : '' }}" data-priority="regular" onclick="selectPriorityType('status', 'regular')">Regular</button>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fname_status">First Name <span style="color: #dc2626;">*</span></label>
                                    <input type="text" name="fname_status" id="fname_status" value="{{ old('fname_status') }}" pattern="[A-Za-zÑñ\s\-']+" placeholder="Enter first name">
                                </div>
                                <div class="form-group">
                                    <label for="mname_status">Middle Name</label>
                                    <input type="text" name="mname_status" id="mname_status" value="{{ old('mname_status') }}" pattern="[A-Za-zÑñ\s\-']*" placeholder="Enter middle name">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="lname_status">Last Name <span style="color: #dc2626;">*</span></label>
                                    <input type="text" name="lname_status" id="lname_status" value="{{ old('lname_status') }}" pattern="[A-Za-zÑñ\s\-']+" placeholder="Enter last name">
                                </div>
                                <div class="form-group">
                                    <label for="suffix_status">Suffix</label>
                                    <input type="text" name="suffix_status" id="suffix_status" value="{{ old('suffix_status') }}" pattern="[A-Za-zÑñ\s\-'.]*" placeholder="Jr., Sr., III">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="age_category_status">Age Category <span style="color: #dc2626;">*</span></label>
                                    <div class="age-category-buttons">
                                        <input type="hidden" name="age_category_status" id="age_category_status" value="{{ old('age_category_status') }}">
                                        <button type="button" class="age-btn {{ old('age_category_status') == '0-4 years old' ? 'active' : '' }}" data-age="0-4 years old" onclick="selectAge('status', '0-4 years old')">0-4 years old</button>
                                        <button type="button" class="age-btn {{ old('age_category_status') == '5 years old and above' ? 'active' : '' }}" data-age="5 years old and above" onclick="selectAge('status', '5 years old and above')">5 years old and above</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="birthdate_status">Birthdate <span style="color: #dc2626;">*</span></label>
                                    <input type="date" name="birthdate_status" id="birthdate_status" value="{{ old('birthdate_status') }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group" style="flex: 2;">
                                    <label for="trn">Transaction Reference Number (TRN)</label>
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <input type="text" name="trn" id="trn" value="{{ old('trn') }}" placeholder="Scan QR code or type TRN manually" autocomplete="off" style="flex: 1;">
                                        <button type="button" id="openScannerBtn" class="btn btn-primary scan-btn" style="white-space: nowrap; padding: 8px 16px; min-width: 100px; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                            </svg>
                                            Scan QR
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Updating Form --}}
                        <div id="updatingForm" class="category-form" style="display: {{ old('category', session('last_category')) == 'Updating' ? 'block' : 'none' }};">
                            <div style="border-left: 4px solid #2563eb; padding-left: 15px; margin-bottom: 20px;">
                                <h4 style="margin: 0 0 5px 0; font-size: 16px;">Updating Details</h4>
                                <p style="margin: 0; color: #6b7280; font-size: 13px;">Please fill in the required information below</p>
                            </div>

                            <div class="form-group" style="margin-top: 15px; margin-bottom: 20px;">
                                <label>Priority Type <span style="color: #dc2626;">*</span></label>
                                <div class="priority-buttons">
                                    <input type="hidden" name="priority_type_update" id="priority_type_update" value="{{ old('priority_type_update', session('last_priority_update', 'regular')) }}">
                                    <button type="button" class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'senior' ? 'active' : '' }}" data-priority="senior" onclick="selectPriorityType('update', 'senior')">Senior</button>
                                    <button type="button" class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'infant' ? 'active' : '' }}" data-priority="infant" onclick="selectPriorityType('update', 'infant')">Infant</button>
                                    <button type="button" class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'pwd' ? 'active' : '' }}" data-priority="pwd" onclick="selectPriorityType('update', 'pwd')">PWD</button>
                                    <button type="button" class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'pregnant' ? 'active' : '' }}" data-priority="pregnant" onclick="selectPriorityType('update', 'pregnant')">Pregnant</button>
                                    <button type="button" class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'regular' ? 'active' : '' }}" data-priority="regular" onclick="selectPriorityType('update', 'regular')">Regular</button>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fname_update">First Name <span style="color: #dc2626;">*</span></label>
                                    <input type="text" name="fname_update" id="fname_update" value="{{ old('fname_update') }}" pattern="[A-Za-zÑñ\s\-']+" placeholder="Enter first name">
                                </div>
                                <div class="form-group">
                                    <label for="mname_update">Middle Name</label>
                                    <input type="text" name="mname_update" id="mname_update" value="{{ old('mname_update') }}" pattern="[A-Za-zÑñ\s\-']*" placeholder="Enter middle name">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="lname_update">Last Name <span style="color: #dc2626;">*</span></label>
                                    <input type="text" name="lname_update" id="lname_update" value="{{ old('lname_update') }}" pattern="[A-Za-zÑñ\s\-']+" placeholder="Enter last name">
                                </div>
                                <div class="form-group">
                                    <label for="suffix_update">Suffix</label>
                                    <input type="text" name="suffix_update" id="suffix_update" value="{{ old('suffix_update') }}" pattern="[A-Za-zÑñ\s\-'.]*" placeholder="Jr., Sr., III">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="age_category_update">Age Category <span style="color: #dc2626;">*</span></label>
                                    <div class="age-category-buttons">
                                        <input type="hidden" name="age_category_update" id="age_category_update" value="{{ old('age_category_update') }}">
                                        <button type="button" class="age-btn {{ old('age_category_update') == '0-4 years old' ? 'active' : '' }}" data-age="0-4 years old" onclick="selectAge('update', '0-4 years old')">0-4 years old</button>
                                        <button type="button" class="age-btn {{ old('age_category_update') == '5 years old and above' ? 'active' : '' }}" data-age="5 years old and above" onclick="selectAge('update', '5 years old and above')">5 years old and above</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="birthdate_update">Birthdate <span style="color: #dc2626;">*</span></label>
                                    <input type="date" name="birthdate_update" id="birthdate_update" value="{{ old('birthdate_update') }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="PCN">PhilSys Card Number (PCN)</label>
                                    <input type="text" name="PCN" id="PCN" value="{{ old('PCN') }}" placeholder="Enter PCN">
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary" id="submitBtn">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
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
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                        Today's Appointments
                    </h3>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #2563eb, #1d4ed8)">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
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
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
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
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Completed</span>
                                <span class="stat-value completed" id="completedCount">{{ $completedCount ?? 0 }}</span>
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
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                        <input type="text" id="searchAppointments" placeholder="Search by name or queue number...">
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
                                        $createdTime = \Carbon\Carbon::parse($appointment->date)->setTimezone('Asia/Manila');
                                        $status = $appointment->status ?? 'pending';
                                        $showInTable = in_array($status, ['pending', 'serving']) || (!$appointment->time_catered && !in_array($status, ['completed', 'cancelled', 'no_show']));
                                        $priorityType = $appointment->priority_type ?? 'regular';
                                        $priorityDisplay = ucfirst($priorityType);
                                        $fullName = $appointment->lname . ', ' . $appointment->fname;
                                        if ($appointment->mname && trim($appointment->mname) !== '') $fullName .= ' ' . $appointment->mname;
                                        if ($appointment->suffix && trim($appointment->suffix) !== '') $fullName .= ' ' . $appointment->suffix;
                                    @endphp
                                    @if ($showInTable)
                                        <tr data-search="{{ strtolower($appointment->lname . ' ' . $appointment->fname) }}">
                                            <td><span class="queue-number">{{ $appointment->q_id }}</span></td>
                                            <td><div class="client-name">{{ $fullName }}</div></td>
                                            <td><span class="priority-badge priority-{{ $priorityType }}">{{ strtoupper($priorityDisplay) }}</span></td>
                                            <td>{{ $appointment->queue_for }}</td>
                                            <td>{{ $createdTime->format('h:i A') }}</td>
                                            <td>
                                                <button type="button" class="btn-action cancel-btn" data-id="{{ $appointment->n_id }}" data-name="{{ $fullName }}" data-queue="{{ $appointment->q_id }}" onclick="cancelAppointment(this)">
                                                    <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                    Cancel
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr><td colspan="6" class="empty-state"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg><p>No pending appointments for today</p></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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
        $.ajax({ url: '{{ route('appointment.store-category') }}', method: 'POST', data: { _token: '{{ csrf_token() }}', category: category } });
    }

    function selectAge(formType, ageValue) {
        const hiddenInput = document.getElementById(`age_category_${formType}`);
        if (hiddenInput) hiddenInput.value = ageValue;
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
        if (hiddenInput) hiddenInput.value = priorityValue;
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
        else selectAge(formType, '5 years old and above');
        $.ajax({ url: '{{ route('appointment.store-priority') }}', method: 'POST', data: { _token: '{{ csrf_token() }}', priority_type: priorityValue, form_type: formType } });
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
                Swal.fire({ title: 'Success!', text: 'QR Code scanned successfully!', icon: 'success', timer: 1500, showConfirmButton: false, toast: true });
            };
            const cameras = await Html5Qrcode.getCameras();
            let selectedCameraId = cameras[0]?.id;
            for (const camera of cameras) {
                if (camera.label.toLowerCase().includes('back') || camera.label.includes('environment')) {
                    selectedCameraId = camera.id; break;
                }
            }
            await html5QrcodeScanner.start({ deviceId: selectedCameraId }, { fps: 10, qrbox: { width: 250, height: 250 } }, qrCodeSuccessCallback);
            isScanning = true;
        } catch (err) {
            console.error('Scanner error:', err);
            Swal.fire({ title: 'Error', text: 'Unable to access camera', icon: 'error' });
        }
    }

    function showScannerModal() {
        document.getElementById('scannerModal').style.display = 'flex';
        setTimeout(() => startQRScanner(), 200);
    }

    function closeScannerModal() {
        document.getElementById('scannerModal').style.display = 'none';
        if (html5QrcodeScanner && isScanning) html5QrcodeScanner.stop().then(() => { isScanning = false; });
    }

    function cancelAppointment(button) {
        const appointmentId = button.getAttribute('data-id');
        const clientName = button.getAttribute('data-name');
        const queueNumber = button.getAttribute('data-queue');
        const row = button.closest('tr');
        Swal.fire({
            title: 'Cancel Appointment?',
            html: `Cancel <strong>${queueNumber}</strong> for <strong>${clientName}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Yes, Cancel',
            preConfirm: () => fetch(`/appointment/update-status/${appointmentId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ status: 'cancelled' })
            }).then(res => res.json())
        }).then(result => {
            if (result.isConfirmed) {
                row.remove();
                if (document.querySelector('#appointmentsTableBody tr:not(.empty-state)') === null) {
                    document.getElementById('appointmentsTableBody').innerHTML = '<tr><td colspan="6" class="empty-state"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg><p>No pending appointments for today</p></td></tr>';
                }
                Swal.fire({ title: 'Cancelled!', text: `${queueNumber} cancelled`, icon: 'success', timer: 2000, showConfirmButton: false });
                fetchAppointments();
            }
        });
    }

    function fetchAppointments() {
        fetch('{{ route('appointment.today') }}?_=' + new Date().getTime(), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        }).then(response => response.json()).then(data => {
            if (data.success) {
                document.getElementById('totalQueue').textContent = data.stats.total;
                document.getElementById('pendingCount').textContent = data.stats.pending;
                document.getElementById('completedCount').textContent = data.stats.completed;
                updateAppointmentsTable(data.appointments);
            }
        }).catch(error => console.error('Error:', error));
    }

    function updateAppointmentsTable(appointments) {
        const tbody = document.getElementById('appointmentsTableBody');
        if (!appointments || appointments.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="empty-state"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg><p>No pending appointments for today</p></td></tr>';
            return;
        }
        let html = '';
        appointments.forEach(app => {
            const status = app.status || 'pending';
            const showInTable = ['pending', 'serving'].includes(status) || (!app.time_catered && !['completed', 'cancelled', 'no_show'].includes(status));
            if (showInTable) {
                const createdTime = new Date(app.date).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true, timeZone: 'Asia/Manila' });
                let fullName = app.lname + ', ' + app.fname;
                if (app.mname && app.mname.trim() !== '') fullName += ' ' + app.mname;
                if (app.suffix && app.suffix.trim() !== '') fullName += ' ' + app.suffix;
                const priorityType = app.priority_type?.toLowerCase() || 'regular';
                const priorityDisplay = priorityType.charAt(0).toUpperCase() + priorityType.slice(1);
                html += `<tr data-search="${(app.lname + ' ' + app.fname).toLowerCase()}">
                    <td><span class="queue-number">${app.q_id}</span></td>
                    <td><div class="client-name">${fullName}</div></td>
                    <td><span class="priority-badge priority-${priorityType}">${priorityDisplay.toUpperCase()}</span></td>
                    <td>${app.queue_for}</td>
                    <td>${createdTime}</td>
                    <td><button class="btn-action cancel-btn" data-id="${app.n_id}" data-name="${fullName}" data-queue="${app.q_id}" onclick="cancelAppointment(this)">Cancel</button></td>
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

    document.addEventListener('DOMContentLoaded', function() {
        const savedCategory = document.getElementById('selectedCategory').value;
        selectCategory(savedCategory);
        document.getElementById('openScannerBtn')?.addEventListener('click', () => {
            if (document.getElementById('selectedCategory').value !== 'Status Inquiry') {
                Swal.fire({ title: 'Warning!', text: 'Please select Status Inquiry category first.', icon: 'warning' });
                selectCategory('Status Inquiry');
                return;
            }
            showScannerModal();
        });
        document.getElementById('closeModalBtn')?.addEventListener('click', closeScannerModal);
        document.getElementById('cancelScannerBtn')?.addEventListener('click', closeScannerModal);
        document.querySelectorAll('.service-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.service-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                const service = this.dataset.service;
                const rows = document.querySelectorAll('#appointmentsTableBody tr');
                rows.forEach(row => {
                    if (row.classList.contains('empty-state')) return;
                    const serviceCell = row.querySelector('td:nth-child(4)')?.textContent;
                    row.style.display = (service === 'all' || serviceCell === service) ? '' : 'none';
                });
            });
        });
        document.getElementById('searchAppointments')?.addEventListener('keyup', function() {
            currentSearchTerm = this.value.toLowerCase();
            filterTableRows();
        });
        refreshInterval = setInterval(fetchAppointments, 10000);
        fetchAppointments();
    });
</script>