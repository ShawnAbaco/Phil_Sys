<x-header title="Appointment Issuance" />

<!-- Main Container -->
<main class="main-container">
    <div class="dashboard-grid">
        {{-- Issue New Appointment Card --}}
        <div class="card">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
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
                        style="display: block; margin-bottom: 10px; font-weight: 600; color: #1f2937; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em;">Select
                        Category:</label>
                    <div style="display: flex; gap: 12px; margin-top: 5px;">
                        <button type="button"
                            class="category-btn {{ old('category', session('last_category')) == 'NID Registration' ? 'active' : '' }}"
                            data-category="NID Registration" onclick="selectCategory('NID Registration')"
                            style="padding: 12px 20px; border: 2px solid #e5e7eb; border-radius: 10px; background-color: {{ old('category', session('last_category')) == 'NID Registration' ? '#2563eb' : 'white' }}; color: {{ old('category', session('last_category')) == 'NID Registration' ? 'white' : '#374151' }}; font-weight: 600; cursor: pointer; transition: all 0.3s ease; flex: 1; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
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
                            data-category="Status Inquiry" onclick="selectCategory('Status Inquiry')"
                            style="padding: 12px 20px; border: 2px solid #e5e7eb; border-radius: 10px; background-color: {{ old('category', session('last_category')) == 'Status Inquiry' ? '#2563eb' : 'white' }}; color: {{ old('category', session('last_category')) == 'Status Inquiry' ? 'white' : '#374151' }}; font-weight: 600; cursor: pointer; transition: all 0.3s ease; flex: 1; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
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
                            data-category="Updating" onclick="selectCategory('Updating')"
                            style="padding: 12px 20px; border: 2px solid #e5e7eb; border-radius: 10px; background-color: {{ old('category', session('last_category')) == 'Updating' ? '#2563eb' : 'white' }}; color: {{ old('category', session('last_category')) == 'Updating' ? 'white' : '#374151' }}; font-weight: 600; cursor: pointer; transition: all 0.3s ease; flex: 1; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
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

                    {{-- Hidden input to store selected category --}}
                    <input type="hidden" name="category" id="selectedCategory"
                        value="{{ old('category', session('last_category', 'NID Registration')) }}">

                    <!-- NID Registration Form -->
                    <div id="nidForm" class="category-form"
                        style="display: {{ old('category', session('last_category')) == 'NID Registration' ? 'block' : 'none' }};">
                        <div style="border-left: 4px solid #2563eb; padding-left: 15px; margin-bottom: 20px;">
                            <h4 style="margin: 0 0 5px 0; font-size: 16px;">NID Registration Details
                            </h4>
                            <p style="margin: 0; color: #6b7280; font-size: 13px;">Please fill in the required
                                information below</p>
                        </div>

                        <!-- Priority Type Selection for NID Registration -->
                        <div class="form-group" style="margin-top: 15px; margin-bottom: 20px;">
                            <label>Priority Type <span style="color: #dc2626;">*</span></label>
                            <div class="priority-buttons">
                                <input type="hidden" name="priority_type_nid" id="priority_type_nid"
                                    value="{{ old('priority_type_nid', session('last_priority_nid', 'regular')) }}">

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'senior' ? 'active' : '' }}"
                                    data-priority="senior" onclick="selectPriorityType('nid', 'senior')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                        </svg>
                                        Senior
                                    </span>
                                </button>

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'infant' ? 'active' : '' }}"
                                    data-priority="infant" onclick="selectPriorityType('nid', 'infant')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" />
                                        </svg>
                                        Infant
                                    </span>
                                </button>

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'pwd' ? 'active' : '' }}"
                                    data-priority="pwd" onclick="selectPriorityType('nid', 'pwd')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 110-12 6 6 0 010 12zM9 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm1 7a1 1 0 100-2 1 1 0 000 2z" />
                                        </svg>
                                        PWD
                                    </span>
                                </button>

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'pregnant' ? 'active' : '' }}"
                                    data-priority="pregnant" onclick="selectPriorityType('nid', 'pregnant')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                                            <path
                                                d="M12 2a2 2 0 110 4 2 2 0 010-4zm-1 5c-1.66 0-3 1.34-3 3v3h2v7h2v-6h2.5c1.38 0 2.5 1.12 2.5 2.5V20h2v-3.5c0-2.49-2.01-4.5-4.5-4.5H12V10c0-.55.45-1 1-1h3V7h-5z" />
                                        </svg>
                                        Pregnant
                                    </span>
                                </button>

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_nid', session('last_priority_nid')) == 'regular' ? 'active' : '' }}"
                                    data-priority="regular" onclick="selectPriorityType('nid', 'regular')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm1 2h3v2H5V6zm5 0h3v2h-3V6zm-5 4h3v2H5v-2zm5 0h3v2h-3v-2z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Regular
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="fname_nid">First Name <span style="color: #dc2626;">*</span></label>
                                <input type="text" name="fname_nid" id="fname_nid"
                                    value="{{ old('fname_nid') }}" data-required="true"
                                    pattern="[A-Za-zÑñ\s\-']+" 
                                    title="Only letters, spaces, hyphens, and apostrophes are allowed"
                                    class="@error('fname_nid') is-invalid @enderror" 
                                    placeholder="Enter first name">
                            </div>
                            <div class="form-group">
                                <label for="mname_nid">Middle Name</label>
                                <input type="text" name="mname_nid" id="mname_nid"
                                    value="{{ old('mname_nid') }}" 
                                    pattern="[A-Za-zÑñ\s\-']*" 
                                    title="Only letters, spaces, hyphens, and apostrophes are allowed"
                                    placeholder="Enter middle name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="lname_nid">Last Name <span style="color: #dc2626;">*</span></label>
                                <input type="text" name="lname_nid" id="lname_nid"
                                    value="{{ old('lname_nid') }}" data-required="true"
                                    pattern="[A-Za-zÑñ\s\-']+" 
                                    title="Only letters, spaces, hyphens, and apostrophes are allowed"
                                    class="@error('lname_nid') is-invalid @enderror" 
                                    placeholder="Enter last name">
                            </div>
                            <div class="form-group">
                                <label for="suffix_nid">Suffix</label>
                                <input type="text" name="suffix_nid" id="suffix_nid"
                                    value="{{ old('suffix_nid') }}" 
                                    pattern="[A-Za-zÑñ\s\-'.]*" 
                                    title="Only letters, spaces, hyphens, apostrophes, and periods are allowed"
                                    placeholder="Jr., Sr., III">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="age_category_nid">Age Category <span
                                        style="color: #dc2626;">*</span></label>
                                <div class="age-category-buttons" style="display: flex; gap: 10px; margin-top: 5px;">
                                    <input type="hidden" name="age_category_nid" id="age_category_nid"
                                        value="{{ old('age_category_nid') }}">
                                    <button type="button"
                                        class="age-btn {{ old('age_category_nid') == '0-4 years old' ? 'active' : '' }}"
                                        data-age="0-4 years old" onclick="selectAge('nid', '0-4 years old')"
                                        style="padding: 10px 16px; border: 2px solid #e5e7eb; border-radius: 8px; background-color: {{ old('age_category_nid') == '0-4 years old' ? '#10B981' : 'white' }}; color: {{ old('age_category_nid') == '0-4 years old' ? 'white' : '#374151' }}; font-weight: 500; cursor: pointer; transition: all 0.3s ease; flex: 1; font-size: 13px;">
                                        <span
                                            style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="16"
                                                height="16">
                                                <path
                                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                            </svg>
                                            0-4 years old
                                        </span>
                                    </button>
                                    <button type="button"
                                        class="age-btn {{ old('age_category_nid') == '5 years old and above' ? 'active' : '' }}"
                                        data-age="5 years old and above"
                                        onclick="selectAge('nid', '5 years old and above')"
                                        style="padding: 10px 16px; border: 2px solid #e5e7eb; border-radius: 8px; background-color: {{ old('age_category_nid') == '5 years old and above' ? '#10B981' : 'white' }}; color: {{ old('age_category_nid') == '5 years old and above' ? 'white' : '#374151' }}; font-weight: 500; cursor: pointer; transition: all 0.3s ease; flex: 1; font-size: 13px;">
                                        <span
                                            style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="16"
                                                height="16">
                                                <path fill-rule="evenodd"
                                                    d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            5 years old and above
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="birthdate_nid">Birthdate <span style="color: #dc2626;">*</span></label>
                                <input type="date" name="birthdate_nid" id="birthdate_nid"
                                    value="{{ old('birthdate_nid') }}" data-required="true"
                                    class="@error('birthdate_nid') is-invalid @enderror">
                            </div>
                        </div>
                    </div>

                    <!-- Status Inquiry Form (UPDATED with pattern attributes) -->
                    <div id="statusForm" class="category-form"
                        style="display: {{ old('category', session('last_category')) == 'Status Inquiry' ? 'block' : 'none' }};">
                        <div style="border-left: 4px solid #2563eb; padding-left: 15px; margin-bottom: 20px;">
                            <h4 style="margin: 0 0 5px 0; font-size: 16px;">Status Inquiry Details</h4>
                            <p style="margin: 0; color: #6b7280; font-size: 13px;">Please fill in the required
                                information below</p>
                        </div>

                        <!-- Priority Type Selection for Status Inquiry -->
                        <div class="form-group" style="margin-top: 15px; margin-bottom: 20px;">
                            <label>Priority Type <span style="color: #dc2626;">*</span></label>
                            <div class="priority-buttons">
                                <input type="hidden" name="priority_type_status" id="priority_type_status"
                                    value="{{ old('priority_type_status', session('last_priority_status', 'regular')) }}">

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'senior' ? 'active' : '' }}"
                                    data-priority="senior" onclick="selectPriorityType('status', 'senior')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                        </svg>
                                        Senior
                                    </span>
                                </button>

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'infant' ? 'active' : '' }}"
                                    data-priority="infant" onclick="selectPriorityType('status', 'infant')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" />
                                        </svg>
                                        Infant
                                    </span>
                                </button>

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'pwd' ? 'active' : '' }}"
                                    data-priority="pwd" onclick="selectPriorityType('status', 'pwd')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 110-12 6 6 0 010 12zM9 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm1 7a1 1 0 100-2 1 1 0 000 2z" />
                                        </svg>
                                        PWD
                                    </span>
                                </button>

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'pregnant' ? 'active' : '' }}"
                                    data-priority="pregnant" onclick="selectPriorityType('status', 'pregnant')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                                            <path
                                                d="M12 2a2 2 0 110 4 2 2 0 010-4zm-1 5c-1.66 0-3 1.34-3 3v3h2v7h2v-6h2.5c1.38 0 2.5 1.12 2.5 2.5V20h2v-3.5c0-2.49-2.01-4.5-4.5-4.5H12V10c0-.55.45-1 1-1h3V7h-5z" />
                                        </svg>
                                        Pregnant
                                    </span>
                                </button>

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_status', session('last_priority_status')) == 'regular' ? 'active' : '' }}"
                                    data-priority="regular" onclick="selectPriorityType('status', 'regular')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm1 2h3v2H5V6zm5 0h3v2h-3V6zm-5 4h3v2H5v-2zm5 0h3v2h-3v-2z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Regular
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="fname_status">First Name <span style="color: #dc2626;">*</span></label>
                                <input type="text" name="fname_status" id="fname_status"
                                    value="{{ old('fname_status') }}" data-required="true"
                                    pattern="[A-Za-zÑñ\s\-']+" 
                                    title="Only letters, spaces, hyphens, and apostrophes are allowed"
                                    class="@error('fname_status') is-invalid @enderror"
                                    placeholder="Enter first name">
                            </div>
                            <div class="form-group">
                                <label for="mname_status">Middle Name</label>
                                <input type="text" name="mname_status" id="mname_status"
                                    value="{{ old('mname_status') }}" 
                                    pattern="[A-Za-zÑñ\s\-']*" 
                                    title="Only letters, spaces, hyphens, and apostrophes are allowed"
                                    placeholder="Enter middle name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="lname_status">Last Name <span style="color: #dc2626;">*</span></label>
                                <input type="text" name="lname_status" id="lname_status"
                                    value="{{ old('lname_status') }}" data-required="true"
                                    pattern="[A-Za-zÑñ\s\-']+" 
                                    title="Only letters, spaces, hyphens, and apostrophes are allowed"
                                    class="@error('lname_status') is-invalid @enderror" 
                                    placeholder="Enter last name">
                            </div>
                            <div class="form-group">
                                <label for="suffix_status">Suffix</label>
                                <input type="text" name="suffix_status" id="suffix_status"
                                    value="{{ old('suffix_status') }}" 
                                    pattern="[A-Za-zÑñ\s\-'.]*" 
                                    title="Only letters, spaces, hyphens, apostrophes, and periods are allowed"
                                    placeholder="Jr., Sr., III">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="age_category_status">Age Category <span
                                        style="color: #dc2626;">*</span></label>
                                <div class="age-category-buttons" style="display: flex; gap: 10px; margin-top: 5px;">
                                    <input type="hidden" name="age_category_status" id="age_category_status"
                                        value="{{ old('age_category_status') }}">
                                    <button type="button"
                                        class="age-btn {{ old('age_category_status') == '0-4 years old' ? 'active' : '' }}"
                                        data-age="0-4 years old" onclick="selectAge('status', '0-4 years old')"
                                        style="padding: 10px 16px; border: 2px solid #e5e7eb; border-radius: 8px; background-color: {{ old('age_category_status') == '0-4 years old' ? '#10B981' : 'white' }}; color: {{ old('age_category_status') == '0-4 years old' ? 'white' : '#374151' }}; font-weight: 500; cursor: pointer; transition: all 0.3s ease; flex: 1; font-size: 13px;">
                                        <span
                                            style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="16"
                                                height="16">
                                                <path
                                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                            </svg>
                                            0-4 years old
                                        </span>
                                    </button>
                                    <button type="button"
                                        class="age-btn {{ old('age_category_status') == '5 years old and above' ? 'active' : '' }}"
                                        data-age="5 years old and above"
                                        onclick="selectAge('status', '5 years old and above')"
                                        style="padding: 10px 16px; border: 2px solid #e5e7eb; border-radius: 8px; background-color: {{ old('age_category_status') == '5 years old and above' ? '#10B981' : 'white' }}; color: {{ old('age_category_status') == '5 years old and above' ? 'white' : '#374151' }}; font-weight: 500; cursor: pointer; transition: all 0.3s ease; flex: 1; font-size: 13px;">
                                        <span
                                            style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="16"
                                                height="16">
                                                <path fill-rule="evenodd"
                                                    d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            5 years old and above
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="birthdate_status">Birthdate <span style="color: #dc2626;">*</span></label>
                                <input type="date" name="birthdate_status" id="birthdate_status"
                                    value="{{ old('birthdate_status') }}" data-required="true"
                                    class="@error('birthdate_status') is-invalid @enderror">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 2;">
                                <label for="trn">Transaction Reference Number (TRN)</label>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <input type="text" name="trn" id="trn" value="{{ old('trn') }}"
                                        placeholder="Scan QR code or type TRN manually" autocomplete="off"
                                        class="@error('trn') is-invalid @enderror" 
                                        style="flex: 1;">
                                    <button type="button" id="openScannerBtn" class="btn btn-primary scan-btn"
                                        style="white-space: nowrap; padding: 8px 16px; min-width: 100px; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-weight: 500; box-shadow: 0 2px 4px rgba(37,99,235,0.3);">
                                        <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
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

                    <!-- Updating Form (UPDATED with pattern attributes) -->
                    <div id="updatingForm" class="category-form"
                        style="display: {{ old('category', session('last_category')) == 'Updating' ? 'block' : 'none' }};">
                        <div style="border-left: 4px solid #2563eb; padding-left: 15px; margin-bottom: 20px;">
                            <h4 style="margin: 0 0 5px 0; font-size: 16px;">Updating Details</h4>
                            <p style="margin: 0; color: #6b7280; font-size: 13px;">Please fill in the required
                                information below</p>
                        </div>

                        <!-- Priority Type Selection for Updating -->
                        <div class="form-group" style="margin-top: 15px; margin-bottom: 20px;">
                            <label>Priority Type <span style="color: #dc2626;">*</span></label>
                            <div class="priority-buttons">
                                <input type="hidden" name="priority_type_update" id="priority_type_update"
                                    value="{{ old('priority_type_update', session('last_priority_update', 'regular')) }}">

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'senior' ? 'active' : '' }}"
                                    data-priority="senior" onclick="selectPriorityType('update', 'senior')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                        </svg>
                                        Senior
                                    </span>
                                </button>

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'infant' ? 'active' : '' }}"
                                    data-priority="infant" onclick="selectPriorityType('update', 'infant')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" />
                                        </svg>
                                        Infant
                                    </span>
                                </button>

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'pwd' ? 'active' : '' }}"
                                    data-priority="pwd" onclick="selectPriorityType('update', 'pwd')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 110-12 6 6 0 010 12zM9 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm1 7a1 1 0 100-2 1 1 0 000 2z" />
                                        </svg>
                                        PWD
                                    </span>
                                </button>

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'pregnant' ? 'active' : '' }}"
                                    data-priority="pregnant" onclick="selectPriorityType('update', 'pregnant')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                                            <path
                                                d="M12 2a2 2 0 110 4 2 2 0 010-4zm-1 5c-1.66 0-3 1.34-3 3v3h2v7h2v-6h2.5c1.38 0 2.5 1.12 2.5 2.5V20h2v-3.5c0-2.49-2.01-4.5-4.5-4.5H12V10c0-.55.45-1 1-1h3V7h-5z" />
                                        </svg>
                                        Pregnant
                                    </span>
                                </button>

                                <button type="button"
                                    class="priority-btn {{ old('priority_type_update', session('last_priority_update')) == 'regular' ? 'active' : '' }}"
                                    data-priority="regular" onclick="selectPriorityType('update', 'regular')">
                                    <span class="btn-content">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm1 2h3v2H5V6zm5 0h3v2h-3V6zm-5 4h3v2H5v-2zm5 0h3v2h-3v-2z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Regular
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="fname_update">First Name <span style="color: #dc2626;">*</span></label>
                                <input type="text" name="fname_update" id="fname_update"
                                    value="{{ old('fname_update') }}" data-required="true"
                                    pattern="[A-Za-zÑñ\s\-']+" 
                                    title="Only letters, spaces, hyphens, and apostrophes are allowed"
                                    class="@error('fname_update') is-invalid @enderror"
                                    placeholder="Enter first name">
                            </div>
                            <div class="form-group">
                                <label for="mname_update">Middle Name</label>
                                <input type="text" name="mname_update" id="mname_update"
                                    value="{{ old('mname_update') }}" 
                                    pattern="[A-Za-zÑñ\s\-']*" 
                                    title="Only letters, spaces, hyphens, and apostrophes are allowed"
                                    placeholder="Enter middle name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="lname_update">Last Name <span style="color: #dc2626;">*</span></label>
                                <input type="text" name="lname_update" id="lname_update"
                                    value="{{ old('lname_update') }}" data-required="true"
                                    pattern="[A-Za-zÑñ\s\-']+" 
                                    title="Only letters, spaces, hyphens, and apostrophes are allowed"
                                    class="@error('lname_update') is-invalid @enderror" 
                                    placeholder="Enter last name">
                            </div>
                            <div class="form-group">
                                <label for="suffix_update">Suffix</label>
                                <input type="text" name="suffix_update" id="suffix_update"
                                    value="{{ old('suffix_update') }}" 
                                    pattern="[A-Za-zÑñ\s\-'.]*" 
                                    title="Only letters, spaces, hyphens, apostrophes, and periods are allowed"
                                    placeholder="Jr., Sr., III">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="age_category_update">Age Category <span
                                        style="color: #dc2626;">*</span></label>
                                <div class="age-category-buttons" style="display: flex; gap: 10px; margin-top: 5px;">
                                    <input type="hidden" name="age_category_update" id="age_category_update"
                                        value="{{ old('age_category_update') }}">
                                    <button type="button"
                                        class="age-btn {{ old('age_category_update') == '0-4 years old' ? 'active' : '' }}"
                                        data-age="0-4 years old" onclick="selectAge('update', '0-4 years old')"
                                        style="padding: 10px 16px; border: 2px solid #e5e7eb; border-radius: 8px; background-color: {{ old('age_category_update') == '0-4 years old' ? '#10B981' : 'white' }}; color: {{ old('age_category_update') == '0-4 years old' ? 'white' : '#374151' }}; font-weight: 500; cursor: pointer; transition: all 0.3s ease; flex: 1; font-size: 13px;">
                                        <span
                                            style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="16"
                                                height="16">
                                                <path
                                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                            </svg>
                                            0-4 years old
                                        </span>
                                    </button>
                                    <button type="button"
                                        class="age-btn {{ old('age_category_update') == '5 years old and above' ? 'active' : '' }}"
                                        data-age="5 years old and above"
                                        onclick="selectAge('update', '5 years old and above')"
                                        style="padding: 10px 16px; border: 2px solid #e5e7eb; border-radius: 8px; background-color: {{ old('age_category_update') == '5 years old and above' ? '#10B981' : 'white' }}; color: {{ old('age_category_update') == '5 years old and above' ? 'white' : '#374151' }}; font-weight: 500; cursor: pointer; transition: all 0.3s ease; flex: 1; font-size: 13px;">
                                        <span
                                            style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="16"
                                                height="16">
                                                <path fill-rule="evenodd"
                                                    d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            5 years old and above
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="birthdate_update">Birthdate <span style="color: #dc2626;">*</span></label>
                                <input type="date" name="birthdate_update" id="birthdate_update"
                                    value="{{ old('birthdate_update') }}" data-required="true"
                                    class="@error('birthdate_update') is-invalid @enderror">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="PCN">PhilSys Card Number (PCN)</label>
                                <input type="text" name="PCN" id="PCN" value="{{ old('PCN') }}"
                                    class="@error('PCN') is-invalid @enderror" 
                                    placeholder="Enter PCN">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions"
                        style="margin-top: 25px; border-top: 1px solid #e5e7eb; padding-top: 20px;">
                        <button type="submit" class="btn btn-primary" id="submitBtn"
                            style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border: none; padding: 12px 30px; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 10px; font-size: 16px; box-shadow: 0 4px 6px rgba(37,99,235,0.3); transition: all 0.3s ease; width: 100%;">
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

        {{-- Today's Appointments Card (unchanged from your original) --}}
        <div class="card">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    Today's Appointments
                </h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon"
                            style="background: linear-gradient(135deg, var(--psa-blue), var(--psa-red))">
                            <svg viewBox="0 0 20 20" fill="currentColor">
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
                        <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B, #FBBF24)">
                            <svg viewBox="0 0 20 20" fill="currentColor">
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
                        <div class="stat-icon" style="background: linear-gradient(135deg, #10B981, #34D399)">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Completed</span>
                            <span class="stat-value completed" id="completedCount">{{ $completedCount ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="search-box">
                    <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                    <input type="text" id="searchAppointments" placeholder="Search...">
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
                            </tr>
                        </thead>
                        <tbody class="appointments-table-body" id="appointmentsTableBody">
                            @forelse($appointments as $appointment)
                                @php
                                    $servedTime = $appointment->time_catered
                                        ? \Carbon\Carbon::parse($appointment->time_catered)->setTimezone('Asia/Manila')
                                        : null;
                                    $createdTime = \Carbon\Carbon::parse($appointment->date)->setTimezone(
                                        'Asia/Manila',
                                    );

                                    $status = $appointment->status ?? 'pending';
                                    $showInTable =
                                        in_array($status, ['pending', 'serving']) ||
                                        (!$appointment->time_catered &&
                                            !in_array($status, ['completed', 'cancelled', 'no_show']));

                                    $serviceDisplay = $appointment->queue_for;
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
                                    <tr
                                        data-search="{{ strtolower($appointment->lname . ' ' . $appointment->fname . ' ' . $appointment->trn) }}">
                                        <td><span class="queue-number">{{ $appointment->q_id }}</span></td>
                                        <td>
                                            <div class="client-name">
                                                {{ $fullName }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="priority-badge priority-{{ $priorityType }}"
                                                data-priority="{{ $priorityType }}">
                                                {{ strtoupper($priorityDisplay) }}
                                            </span>
                                        </td>
                                        <td>{{ $serviceDisplay }}</td>
                                        <td>{{ $createdTime->format('h:i A') }}</td>
                                        <td>
                                            <button type="button" 
                                                    class="btn-action cancel-btn" 
                                                    data-id="{{ $appointment->n_id }}"
                                                    data-name="{{ $fullName }}"
                                                    data-queue="{{ $appointment->q_id }}"
                                                    onclick="cancelAppointment(this)">
                                                <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                                Cancel
                                            </button>
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



    <!-- QR Scanner Modal -->
    <div id="scannerModal" class="scanner-modal" style="display: none;">
        <div class="scanner-modal-content">
            <div class="scanner-modal-header">
                <h3 style="margin: 0; color: #2563eb; display: flex; align-items: center; gap: 8px;">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                        <path fill-rule="evenodd"
                            d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z"
                            clip-rule="evenodd" />
                    </svg>
                    Scan QR Code
                </h3>
                <button type="button" id="closeModalBtn" class="scanner-modal-close">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="scanner-modal-body">
                <div id="qr-reader" style="width: 100%; min-height: 400px;"></div>
            </div>
            <div class="scanner-modal-footer">
                <button type="button" id="cancelScannerBtn" class="btn btn-secondary"
                    style="padding: 8px 16px; background-color: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    {{-- Recent Transactions Card --}}
    <div class="card full-width" id="recentTransactionsCard">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 4a1 1 0 10-2 0v3.586l-.293-.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 10-1.414-1.414l-.293.293V8z"
                        clip-rule="evenodd" />
                </svg>
                Recent Transactions
            </h3>
            <div class="card-actions" style="display: flex; gap: 10px; align-items: center;">
                <span class="badge" id="showingInfo">Showing
                    {{ $completedTransactions->firstItem() }}-{{ $completedTransactions->lastItem() }} of
                    {{ $completedTransactions->total() }}</span>

                {{-- EXPORT PDF BUTTON --}}
                <button type="button" class="btn btn-danger" id="screenerExportPdfBtn"
                    style="padding: 6px 12px; background-color: #dc2626; color: white; border: none; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                            clip-rule="evenodd" />
                    </svg>
                    Export PDF
                </button>
                <button type="button" class="btn btn-success" id="screenerExportExcelBtn"
                    style="padding: 6px 12px; background-color: #059669; color: white; border: none; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2v2h2V6H6zm6 0v2h2V6h-2zm-6 4v2h2v-2H6zm6 0v2h2v-2h-2zm-6 4v2h2v-2H6zm6 0v2h2v-2h-2z"
                            clip-rule="evenodd" />
                    </svg>
                    Export Excel
                </button>
            </div>
        </div>
        <div class="card-body">
            {{-- Optional Service Filter for Screener --}}
            <!-- <div class="filter-section" style="margin-bottom: 15px; display: flex; justify-content: flex-end;">
                <select id="transactionServiceFilter" class="service-filter"
                    style="padding: 6px 12px; border: 2px solid #e5e7eb; border-radius: 30px; font-size: 13px; color: #374151; background: white; cursor: pointer; width: auto;">
                    <option value="all">All Services</option>
                    <option value="NID Registration">NID Registration</option>
                    <option value="Status Inquiry">Status Inquiry</option>
                    <option value="Updating">NID Updating</option>
                </select>
            </div> -->

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Queue #</th>
                            <th>Client</th>
                            <th>Priority</th>
                            <th>Service</th>
                            <th>Served Time</th>
                            <th>Window</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="transactionsTableContainer">
                        {{-- This will be populated by AJAX --}}
                        @include('appointment.partials.transactions-table', [
                            'completedTransactions' => $completedTransactions,
                        ])
                    </tbody>
                </table>
            </div>

            {{-- Enhanced Pagination with Strict 5-Page Blocks --}}
            <div class="enhanced-pagination" id="paginationContainer">
                @include('appointment.partials.pagination-links', [
                    'completedTransactions' => $completedTransactions,
                ])
            </div>
        </div>
    </div>

    {{-- Print Slip Modal (Hidden by default) --}}
    @if (session('printSlip'))
        <div id="printSlipModal" style="display: none;">
            <div id="printContent">
                <div style="text-align: center; font-family: 'Courier New', monospace; padding: 20px;">
                    <h1 style="font-size: 16pt; margin: 0 0 10px 0;">{{ session('printSlip')['header'] }}</h1>
                    <div style="font-size: 10pt; margin-bottom: 5px;">{{ session('printSlip')['name'] }}</div>
                    <div style="font-size: 10pt; margin-bottom: 5px;">{{ session('printSlip')['service'] }}</div>
                    <div style="font-size: 10pt; margin-bottom: 15px;">{{ session('printSlip')['dateTime'] }}</div>
                    <div style="font-size: 28pt; font-weight: bold; letter-spacing: 4px;">
                        {{ session('printSlip')['queueNumber'] }}</div>
                </div>
            </div>
        </div>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js"></script>
    <script>
    let html5QrcodeScanner = null;
    let isScanning = false;
    let currentSearchTerm = '';
    let isLoading = false;
    let refreshInterval;
    let formSubmitted = false;
    let currentPage = 1; // Track current page for pagination

    function selectCategory(category) {
        // Update hidden input
        document.getElementById('selectedCategory').value = category;

        // Update button styles
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.style.backgroundColor = 'white';
            btn.style.color = '#374151';
            btn.style.borderColor = '#e5e7eb';
        });

        // Style the selected button
        const selectedBtn = document.querySelector(`.category-btn[data-category="${category}"]`);
        if (selectedBtn) {
            selectedBtn.classList.add('active');
            selectedBtn.style.backgroundColor = '#2563eb';
            selectedBtn.style.color = 'white';
            selectedBtn.style.borderColor = '#2563eb';
        }

        // Show/hide forms
        toggleForm(category);

        // Store in session via AJAX
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
        console.log('selectAge called with:', formType, ageValue); // Debug log

        // Update hidden input
        const hiddenInput = document.getElementById(`age_category_${formType}`);
        if (hiddenInput) {
            hiddenInput.value = ageValue;
            console.log(`Updated age_category_${formType} to:`, ageValue);
        } else {
            console.error(`Hidden input age_category_${formType} not found`);
            return;
        }

        // Determine which form ID to use
        let formId;
        if (formType === 'nid') {
            formId = 'nidForm';
        } else if (formType === 'status') {
            formId = 'statusForm';
        } else if (formType === 'update') {
            formId = 'updatingForm';
        } else {
            console.error('Unknown form type:', formType);
            return;
        }

        console.log('Looking for buttons in form:', formId);

        // Update button styles for this specific form
        const ageButtons = document.querySelectorAll(`#${formId} .age-btn`);
        console.log(`Found ${ageButtons.length} age buttons in ${formId}`);

        ageButtons.forEach(btn => {
            btn.classList.remove('active');
            btn.style.backgroundColor = 'white';
            btn.style.color = '#374151';
            btn.style.borderColor = '#e5e7eb';
        });

        // Style the selected button
        const selectedBtn = document.querySelector(`#${formId} .age-btn[data-age="${ageValue}"]`);
        if (selectedBtn) {
            selectedBtn.classList.add('active');
            selectedBtn.style.backgroundColor = '#10B981';
            selectedBtn.style.color = 'white';
            selectedBtn.style.borderColor = '#10B981';
            console.log('Selected button styled successfully');
        } else {
            console.error('Selected button not found in', formId);
        }
    }

    function selectPriorityType(formType, priorityValue) {
        // Update hidden input
        const hiddenInput = document.getElementById(`priority_type_${formType}`);
        if (hiddenInput) {
            hiddenInput.value = priorityValue;
        }

        // Determine which form ID to use
        let formId;
        if (formType === 'nid') {
            formId = 'nidForm';
        } else if (formType === 'status') {
            formId = 'statusForm';
        } else if (formType === 'update') {
            formId = 'updatingForm';
        }

        // Update button styles for this specific form
        const priorityButtons = document.querySelectorAll(`#${formId} .priority-btn`);

        priorityButtons.forEach(btn => {
            btn.classList.remove('active');
            btn.style.backgroundColor = 'white';
            btn.style.color = '#374151';
            btn.style.borderColor = '#e5e7eb';
        });

        // Style the selected button
        const selectedBtn = document.querySelector(`#${formId} .priority-btn[data-priority="${priorityValue}"]`);
        if (selectedBtn) {
            selectedBtn.classList.add('active');
            selectedBtn.style.backgroundColor = '#2563eb';
            selectedBtn.style.color = 'white';
            selectedBtn.style.borderColor = '#2563eb';
        }

        // Auto-select age category based on priority
        if (priorityValue === 'infant') {
            selectAge(formType, '0-4 years old');
        } else {
            selectAge(formType, '5 years old and above');
        }

        // Store in session via AJAX
        $.ajax({
            url: '{{ route('appointment.store-priority') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                priority_type: priorityValue,
                form_type: formType
            },
            success: function(response) {
                console.log('Priority saved to session:', priorityValue);
            }
        });
    }

    function toggleForm(category = null) {
        if (!category) {
            category = document.getElementById('selectedCategory').value;
        }

        var nidForm = document.getElementById('nidForm');
        var statusForm = document.getElementById('statusForm');
        var updatingForm = document.getElementById('updatingForm');

        // Hide all forms
        if (nidForm) nidForm.style.display = 'none';
        if (statusForm) statusForm.style.display = 'none';
        if (updatingForm) updatingForm.style.display = 'none';

        // Remove required attributes from all inputs
        [nidForm, statusForm, updatingForm].forEach(formDiv => {
            if (!formDiv) return;
            var inputs = formDiv.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.removeAttribute('required');
            });
        });

        if (category !== 'Status Inquiry' && isScanning) {
            closeScannerModal();
        }

        var activeForm;
        if (category === 'NID Registration') {
            activeForm = nidForm;
        } else if (category === 'Status Inquiry') {
            activeForm = statusForm;
        } else if (category === 'Updating') {
            activeForm = updatingForm;
        }

        if (activeForm) {
            activeForm.style.display = 'block';
            var inputs = activeForm.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.dataset && input.dataset.required === "true") {
                    input.setAttribute('required', 'required');
                }
            });
        }
    }

    async function stopQRScanner() {
        if (html5QrcodeScanner && isScanning) {
            try {
                await html5QrcodeScanner.stop();
                await html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
                isScanning = false;
            } catch (err) {
                console.error('Error stopping scanner:', err);
            }
        }

        const qrReader = document.getElementById('qr-reader');
        if (qrReader) {
            qrReader.innerHTML = '';
        }
    }

    async function startQRScanner() {
        const qrReader = document.getElementById('qr-reader');
        const trnInput = document.getElementById('trn');

        if (!qrReader) {
            console.error('QR reader element not found');
            return;
        }

        await stopQRScanner();

        qrReader.innerHTML = `
            <div class="scanner-loading" style="text-align: center; padding: 60px 20px; color: #2563eb;">
                <svg viewBox="0 0 20 20" fill="currentColor" width="48" height="48" style="animation: spin 1s linear infinite; margin-bottom: 15px;">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                <p>Initializing camera...<br>Please allow camera access when prompted.</p>
            </div>
        `;

        try {
            if (typeof Html5Qrcode === 'undefined') {
                throw new Error('QR Scanner library not loaded. Please refresh the page.');
            }

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error(
                    'Your browser does not support camera access. Please use a modern browser like Chrome, Firefox, or Safari.'
                );
            }

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
                    position: 'top-end',
                    toast: true,
                    showCloseButton: true
                });
            };

            const config = {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                },
                aspectRatio: 1.0
            };

            const cameras = await Html5Qrcode.getCameras();

            if (cameras && cameras.length > 0) {
                let selectedCameraId = cameras[0].id;

                for (const camera of cameras) {
                    const label = camera.label.toLowerCase();
                    if (label.includes('back') || label.includes('environment') || label.includes('rear')) {
                        selectedCameraId = camera.id;
                        break;
                    }
                }

                await html5QrcodeScanner.start({
                        deviceId: selectedCameraId
                    },
                    config,
                    qrCodeSuccessCallback,
                    (errorMessage) => {}
                );

                isScanning = true;
            } else {
                await html5QrcodeScanner.start({
                        facingMode: "environment"
                    },
                    config,
                    qrCodeSuccessCallback,
                    (errorMessage) => {}
                );

                isScanning = true;
            }

        } catch (err) {
            console.error('Scanner error:', err);

            let errorMessage = 'Unable to access camera. ';

            if (err.name === 'NotAllowedError' || err.message.includes('permission')) {
                errorMessage =
                    'Camera access denied. Please allow camera access in your browser settings and try again.';
            } else if (err.name === 'NotFoundError' || err.message.includes('not found')) {
                errorMessage = 'No camera found on this device.';
            } else if (err.name === 'NotReadableError' || err.message.includes('in use')) {
                errorMessage = 'Camera is already in use by another application.';
            } else {
                errorMessage += err.message || 'Please check your camera and try again.';
            }

            qrReader.innerHTML = `
                <div class="scanner-error" style="text-align: center; padding: 40px 20px; color: #dc2626;">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="48" height="48" style="margin-bottom: 15px;">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <p style="margin-bottom: 20px; color: #4b5563;">${errorMessage}</p>
                    <div>
                        <button onclick="startQRScanner()" class="btn btn-primary" style="padding: 8px 16px; background-color: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 8px;">
                            Try Again
                        </button>
                        <button onclick="closeScannerModal()" class="btn btn-secondary" style="padding: 8px 16px; background-color: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Close
                        </button>
                    </div>
                </div>
            `;
        }
    }

    function showScannerModal() {
        const modal = document.getElementById('scannerModal');
        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => {
                startQRScanner();
            }, 200);
        }
    }

    function closeScannerModal() {
        const modal = document.getElementById('scannerModal');
        if (modal) {
            modal.style.display = 'none';
        }
        stopQRScanner();
    }

    function fetchAppointments() {
        if (isLoading) return;

        const timestamp = new Date().getTime();

        fetch('{{ route('appointment.today') }}?_=' + timestamp, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateAppointmentsTable(data.appointments);
                    updateStatistics(data.stats);
                    console.log('Auto-refresh completed at', new Date().toLocaleTimeString(), 'Appointments:', data
                        .appointments.length);
                }
            })
            .catch(error => {
                console.error('Error fetching appointments:', error);
            });
    }

    function fetchRecentTransactions() {
        if (isLoading) return;
        fetchRecentTransactionsPage(currentPage);
    }

    function loadTransactionsPage(url) {
        if (isLoading) return;
        isLoading = true;

        // Extract page number from URL and store it
        const urlParams = new URLSearchParams(url.split('?')[1] || '');
        const pageParam = urlParams.get('page');
        if (pageParam) {
            currentPage = parseInt(pageParam);
        }

        const tableContainer = document.getElementById('transactionsTableContainer');
        const paginationContainer = document.getElementById('paginationContainer');
        const showingInfo = document.getElementById('showingInfo');

        if (!tableContainer || !paginationContainer) {
            isLoading = false;
            return;
        }

        const separator = url.includes('?') ? '&' : '?';
        const fetchUrl = url + separator + '_=' + new Date().getTime();

        tableContainer.classList.add('loading');
        paginationContainer.classList.add('loading');
        tableContainer.style.opacity = '0';
        paginationContainer.style.opacity = '0';

        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) throw new Error(data.message || 'Unknown error occurred');

            setTimeout(() => {
                if (data.table) tableContainer.innerHTML = data.table;
                if (data.pagination) paginationContainer.innerHTML = data.pagination;
                if (data.showing && showingInfo) showingInfo.textContent = data.showing;

                tableContainer.style.opacity = '1';
                paginationContainer.style.opacity = '1';
                tableContainer.classList.remove('loading');
                paginationContainer.classList.remove('loading');

                // Re-attach pagination listeners
                attachPaginationListeners();

                // Re-apply service filter
                const filter = document.getElementById('transactionServiceFilter');
                if (filter) filter.dispatchEvent(new Event('change'));

                // Add success animation
                tableContainer.classList.add('page-change-success');
                paginationContainer.classList.add('page-change-success');
                
                setTimeout(() => {
                    tableContainer.classList.remove('page-change-success');
                    paginationContainer.classList.remove('page-change-success');
                }, 500);

                isLoading = false;
            }, 150);
        })
        .catch(error => {
            console.error('Error loading page:', error);
            tableContainer.style.opacity = '1';
            paginationContainer.style.opacity = '1';
            tableContainer.classList.remove('loading');
            paginationContainer.classList.remove('loading');
            isLoading = false;
            
            Swal.fire({
                title: 'Error!',
                text: 'Failed to load page. Please try again.',
                icon: 'error',
                confirmButtonColor: '#dc2626',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }

    function attachPaginationListeners() {
        document.querySelectorAll(
            '.pagination-nav-btn:not(.disabled), .pagination-arrow:not(.disabled), .page-number:not(.active)'
        ).forEach(link => {
            link.removeEventListener('click', handlePaginationClick);
            link.addEventListener('click', handlePaginationClick);
        });
    }

    function handlePaginationClick(e) {
        e.preventDefault();
        if (!isLoading) {
            loadTransactionsPage(this.href);
        }
    }

    // Service Filter for Recent Transactions
    document.getElementById('transactionServiceFilter')?.addEventListener('change', function() {
        const selectedService = this.value;
        const rows = document.querySelectorAll('#transactionsTableContainer tr');

        rows.forEach(row => {
            if (row.classList.contains('empty-state')) return;

            const service = row.getAttribute('data-service');
            row.style.display = (selectedService === 'all' || service === selectedService) ? '' : 'none';
        });
    });

    // Fetch recent transactions page (for refresh)
    function fetchRecentTransactionsPage(page = null) {
        if (isLoading) return;

        // Get fresh references each time
        const tableContainer = document.getElementById('transactionsTableContainer');
        const paginationContainer = document.getElementById('paginationContainer');
        const showingInfo = document.getElementById('showingInfo');

        if (!tableContainer || !paginationContainer) {
            console.error('Required elements not found');
            return;
        }

        let url = '{{ route('appointment.transactions-page') }}';
        const params = new URLSearchParams();

        // Use the provided page parameter or fall back to currentPage
        const pageToUse = page !== null ? page : currentPage;
        if (pageToUse > 1) {
            params.append('page', pageToUse);
        }

        const queryString = params.toString();
        if (queryString) url += '?' + queryString;
        url += (url.includes('?') ? '&' : '?') + '_=' + new Date().getTime();

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.table) tableContainer.innerHTML = data.table;
                if (data.pagination) paginationContainer.innerHTML = data.pagination;
                if (data.showing && showingInfo) showingInfo.textContent = data.showing;
                
                // Update currentPage from response if available
                if (data.current_page) {
                    currentPage = data.current_page;
                }
                
                // Re-attach pagination listeners AFTER updating the HTML
                attachPaginationListeners();
                
                // Re-apply service filter
                const filter = document.getElementById('transactionServiceFilter');
                if (filter) filter.dispatchEvent(new Event('change'));
            }
        })
        .catch(error => console.error('Error fetching recent transactions:', error));
    }

    // Export PDF with confirmation
    document.getElementById('screenerExportPdfBtn')?.addEventListener('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Export PDF',
            text: 'Export your completed transactions report?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#dc2626',
            confirmButtonText: 'Yes, Export!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route('appointment.export.pdf') }}';

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'success',
                    title: 'PDF Exported Successfully'
                });
            }
        });
    });

    // Export Excel with confirmation
    document.getElementById('screenerExportExcelBtn')?.addEventListener('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Export to Excel',
            text: 'Export your completed transactions report?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#dc2626',
            confirmButtonText: 'Yes, Export!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route('appointment.export.excel') }}';

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Excel Exported Successfully'
                });
            }
        });
    });

    function updateAppointmentsTable(appointments) {
        const tbody = document.getElementById('appointmentsTableBody');

        if (!appointments || appointments.length === 0) {
            tbody.innerHTML = `
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
            `;
            return;
        }

        let html = '';
        appointments.forEach(app => {
            const status = app.status || 'pending';
            const showInTable = ['pending', 'serving'].includes(status) ||
                (!app.time_catered && !['completed', 'cancelled', 'no_show'].includes(status));

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
                const priorityDisplay = priorityType === 'regular' ? 'Regular' :
                    priorityType.charAt(0).toUpperCase() + priorityType.slice(1);

                const searchData = (app.lname + ' ' + app.fname + ' ' + (app.trn || '')).toLowerCase();

                html += `
                    <tr data-search="${searchData}" data-priority="${priorityType}" data-status="${status}" data-id="${app.n_id}">
                        <td><span class="queue-number">${app.q_id}</span></td>
                        <td><div class="client-name">${fullName}</div></td>
                        <td><span class="priority-badge priority-${priorityType}">${priorityDisplay.toUpperCase()}</span></td>
                        <td>${app.queue_for}</td>
                        <td>${createdTime}</td>
                        <td>
                            <button type="button" class="btn-action cancel-btn" 
                                    data-id="${app.n_id}" data-name="${fullName}" data-queue="${app.q_id}"
                                    onclick="cancelAppointment(this)">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Cancel
                            </button>
                        </td>
                    </tr>
                `;
            }
        });

        if (html === '') {
            tbody.innerHTML = `
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
            `;
        } else {
            tbody.innerHTML = html;
            if (currentSearchTerm) filterTableRows();
        }
    }

    function updateStatistics(stats) {
        if (!stats) return;
        if (stats.total !== undefined) document.getElementById('totalQueue').textContent = stats.total;
        if (stats.pending !== undefined) document.getElementById('pendingCount').textContent = stats.pending;
        if (stats.completed !== undefined) document.getElementById('completedCount').textContent = stats.completed;
    }

    function filterTableRows() {
        const rows = document.querySelectorAll('#appointmentsTableBody tr');
        rows.forEach(row => {
            if (row.classList.contains('empty-state')) return;
            const searchData = row.getAttribute('data-search') || row.textContent.toLowerCase();
            row.style.display = searchData.includes(currentSearchTerm) ? '' : 'none';
        });
    }

    function startAutoRefresh() {
        console.log('Auto-refresh started');
        if (refreshInterval) clearInterval(refreshInterval);
        refreshInterval = setInterval(function() {
            console.log('Auto-refresh triggered');
            fetchAppointments();
            fetchRecentTransactions();
        }, 10000);
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden && !isLoading) {
                console.log('Tab became visible, refreshing');
                fetchAppointments();
                fetchRecentTransactions();
            }
        });
    }

    function disableSubmitButton() {
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn && !submitBtn.disabled) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20" style="animation: spin 1s linear infinite; margin-right: 8px;">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                </svg>
                Processing...
            `;
        }
    }

    function restoreSubmitButton() {
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `
                <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                    <path fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
                Issue Appointment
            `;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing...');

        @if (session('success'))
            showSuccessPopup(
                '{{ session('success') }}',
                @if (session('printSlip'))
                    '{{ session('printSlip')['queueNumber'] }}'
                @else
                    null
                @endif
            );
        @endif

        @if (session('error'))
            restoreSubmitButton();
            formSubmitted = false;
            showErrorPopup('{{ session('error') }}');
        @endif

        @if ($errors->any())
            restoreSubmitButton();
            formSubmitted = false;
            let errors = @json($errors->all());
            showErrorPopup('Please fix the following errors:', errors);
        @endif

        const savedCategory = document.getElementById('selectedCategory').value;
        selectCategory(savedCategory);

        console.log('NID Form age buttons:', document.querySelectorAll('#nidForm .age-btn').length);
        console.log('Status Form age buttons:', document.querySelectorAll('#statusForm .age-btn').length);
        console.log('Updating Form age buttons:', document.querySelectorAll('#updatingForm .age-btn').length);

        const inlineScanner = document.getElementById('qr-reader-container');
        if (inlineScanner) inlineScanner.remove();

        const openScannerBtn = document.getElementById('openScannerBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelScannerBtn = document.getElementById('cancelScannerBtn');
        const modal = document.getElementById('scannerModal');
        const appointmentForm = document.getElementById('appointmentForm');

        if (appointmentForm) {
            appointmentForm.addEventListener('submit', function(e) {
                if (formSubmitted) {
                    e.preventDefault();
                    return false;
                }
                if (!this.checkValidity()) return true;
                formSubmitted = true;
                disableSubmitButton();
                return true;
            });
        }

        if (openScannerBtn) {
            openScannerBtn.addEventListener('click', () => {
                const category = document.getElementById('selectedCategory').value;
                if (category !== 'Status Inquiry') {
                    showWarningPopup('Please select Status Inquiry category first.');
                    selectCategory('Status Inquiry');
                    return;
                }
                showScannerModal();
            });
        }

        if (closeModalBtn) closeModalBtn.addEventListener('click', closeScannerModal);
        if (cancelScannerBtn) cancelScannerBtn.addEventListener('click', closeScannerModal);

        window.addEventListener('click', (event) => {
            if (event.target === modal) closeScannerModal();
        });

        attachPaginationListeners();

        @if (session('printSlip'))
            setTimeout(() => {
                const printContent = document.getElementById('printContent').innerHTML;
                const printWindow = window.open('', '_blank', 'width=300,height=250');
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Appointment Slip</title>
                            <style>
                                @media print {
                                    @page { size: 3in 2.5in; margin: 0; }
                                    body {
                                        margin: 0; padding: 20px; width: 2.5in; height: 2in;
                                        font-family: "Courier New", Courier, monospace; font-size: 10pt;
                                        color: #000; box-sizing: border-box; text-align: center;
                                    }
                                }
                                h1 { font-size: 14pt; margin: 0 0 10px 0; }
                                .info { font-size: 10pt; margin-bottom: 5px; }
                                .date-time { font-size: 10pt; margin-bottom: 15px; }
                                .queue-number { font-size: 28pt; font-weight: bold; letter-spacing: 4px; margin: 0; }
                            </style>
                        </head>
                        <body>${printContent}</body>
                    </html>
                `);
                printWindow.document.close();
                printWindow.focus();
                printWindow.onload = () => printWindow.print();
                printWindow.onafterprint = () => printWindow.close();
            }, 500);
        @endif

        const searchInput = document.getElementById('searchAppointments');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                currentSearchTerm = this.value.toLowerCase();
                filterTableRows();
            });
        }

        startAutoRefresh();
        setTimeout(() => {
            fetchAppointments();
            fetchRecentTransactions();
        }, 1000);
    });

    window.addEventListener('beforeunload', function() {
        if (refreshInterval) clearInterval(refreshInterval);
    });

    function showSuccessPopup(message, queueNumber = null) {
        let html = `<div style="text-align: center;">${message}</div>`;
        if (queueNumber) {
            html = `
                <div style="text-align: center;">
                    <div style="font-size: 18px; margin-bottom: 10px;">${message}</div>
                    <div style="font-size: 32px; font-weight: bold; color: #059669; background: #ecfdf5; padding: 15px; border-radius: 10px; margin: 10px 0;">
                        ${queueNumber}
                    </div>
                </div>
            `;
        }
        Swal.fire({
            title: 'Success!',
            html: html,
            icon: 'success',
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'OK',
            timer: 5000,
            timerProgressBar: true,
            showCloseButton: true
        }).then(() => {
            formSubmitted = false;
            restoreSubmitButton();
            const form = document.getElementById('appointmentForm');
            const currentCategory = document.getElementById('selectedCategory').value;
            form.reset();
            document.querySelectorAll('.age-btn').forEach(btn => {
                btn.classList.remove('active');
                btn.style.backgroundColor = 'white';
                btn.style.color = '#374151';
                btn.style.borderColor = '#e5e7eb';
            });
            document.querySelectorAll('[id^="age_category_"]').forEach(input => input.value = '');
            selectCategory(currentCategory);
        });
    }

    function showErrorPopup(message, errors = null) {
        formSubmitted = false;
        restoreSubmitButton();
        let html = `<div style="text-align: center; color: #991b1b;">${message}</div>`;
        if (errors) {
            let errorsList = '<ul style="text-align: left; margin-top: 10px; color: #991b1b;">';
            if (typeof errors === 'object') {
                Object.values(errors).forEach(error => {
                    if (Array.isArray(error)) {
                        error.forEach(err => errorsList += `<li>${err}</li>`);
                    } else {
                        errorsList += `<li>${error}</li>`;
                    }
                });
            }
            errorsList += '</ul>';
            html += errorsList;
        }
        Swal.fire({
            title: 'Error!',
            html: html,
            icon: 'error',
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Try Again',
            showCloseButton: true
        });
    }

    function showWarningPopup(message) {
        Swal.fire({
            title: 'Warning!',
            text: message,
            icon: 'warning',
            confirmButtonColor: '#d97706',
            confirmButtonText: 'OK'
        });
    }

    function showLoading() {
        document.getElementById('loadingModal')?.classList.add('show');
    }

    function hideLoading() {
        document.getElementById('loadingModal')?.classList.remove('show');
    }

    // Cancel Appointment Function
    function cancelAppointment(button) {
        const appointmentId = button.getAttribute('data-id');
        const clientName = button.getAttribute('data-name');
        const queueNumber = button.getAttribute('data-queue');
        const row = button.closest('tr');
        
        Swal.fire({
            title: 'Cancel Appointment?',
            html: `Are you sure you want to cancel appointment <strong>${queueNumber}</strong> for <strong>${clientName}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Cancel',
            cancelButtonText: 'No, Keep',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`/appointment/update-status/${appointmentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: 'cancelled' })
                })
                .then(response => {
                    if (!response.ok) throw new Error(response.statusText);
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                row.remove();
                
                const tbody = document.getElementById('appointmentsTableBody');
                if (tbody.children.length === 0) {
                    tbody.innerHTML = `
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
                    `;
                }
                
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
                
                Toast.fire({
                    icon: 'success',
                    title: `Appointment ${queueNumber} cancelled successfully`
                });
                
                fetchRecentTransactions();
            }
        });
    }
</script>
</main>