@extends('layouts.admin')

@section('title', 'Window Management')
@section('pageTitle', 'Window Management')
@section('pageSubtitle', 'Configure and monitor service windows')

@php $activeMenu = 'windows'; @endphp

@section('content')
<!-- Summary Cards -->
<div class="windows-summary">
    <div class="summary-card">
        <div class="summary-icon" style="background: var(--psa-blue-light);">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z" />
            </svg>
        </div>
        <div>
            <div class="summary-label">Total Windows</div>
            <div class="summary-value">{{ $totalWindows ?? 12 }}</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background: var(--success); color: white;">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <div class="summary-label">Active Windows</div>
            <div class="summary-value">{{ $activeWindows ?? 8 }}</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background: var(--warning); color: white;">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <div class="summary-label">Inactive Windows</div>
            <div class="summary-value">{{ $inactiveWindows ?? 4 }}</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background: var(--psa-red-light);">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
            </svg>
        </div>
        <div>
            <div class="summary-label">Operators Today</div>
            <div class="summary-value">{{ $operatorsToday ?? 15 }}</div>
        </div>
    </div>
</div>

<!-- Windows Grid -->
<div class="windows-grid">
    @for($i = 1; $i <= 12; $i++)
    <div class="window-card" id="window-{{ $i }}">
        <div class="window-header">
            <h3>Window #{{ $i }}</h3>
            <label class="switch">
                <input type="checkbox" class="window-toggle" data-window="{{ $i }}" {{ $i <= 8 ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>
        <div class="window-body">
            <div class="window-operator">
                @php
                    $operator = \App\Models\User::where('window_num', $i)->where('status', 'active')->first();
                @endphp
                @if($operator)
                <div class="operator-info">
                    <div class="operator-avatar">{{ substr($operator->name, 0, 2) }}</div>
                    <div class="operator-details">
                        <strong>{{ $operator->name }}</strong>
                        <span class="operator-status active">Active</span>
                    </div>
                </div>
                @else
                <div class="operator-placeholder">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    <span>No operator assigned</span>
                </div>
                @endif
            </div>
            
            <div class="window-stats">
                <div class="stat-row">
                    <span class="stat-label">Today's Served:</span>
                    <span class="stat-value">{{ rand(15, 45) }}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Avg. Wait Time:</span>
                    <span class="stat-value">{{ rand(3, 8) }} min</span>
                </div>
            </div>
            
            <div class="window-actions">
                <button class="btn-icon" onclick="assignOperator({{ $i }})" title="Assign Operator">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                    </svg>
                </button>
                <button class="btn-icon" onclick="configureWindow({{ $i }})" title="Configure">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                </button>
                <button class="btn-icon" onclick="viewWindowStats({{ $i }})" title="View Stats">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endfor
</div>

<!-- Assign Operator Modal -->
<div class="modal" id="assignOperatorModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Assign Operator to Window <span id="modalWindowNum"></span></h3>
            <button class="modal-close" onclick="closeModal('assignOperatorModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="assignOperatorForm">
                @csrf
                <input type="hidden" id="window_id" name="window_id">
                <div class="form-group">
                    <label for="operator_id">Select Operator</label>
                    <select id="operator_id" name="operator_id" class="form-control" required>
                        <option value="">Choose an operator...</option>
                        @foreach($availableOperators ?? [] as $operator)
                        <option value="{{ $operator->id }}">{{ $operator->name }} ({{ $operator->designation }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="closeModal('assignOperatorModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Operator</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Configure Window Modal -->
<div class="modal" id="configureWindowModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Configure Window <span id="configWindowNum"></span></h3>
            <button class="modal-close" onclick="closeModal('configureWindowModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="configureWindowForm">
                @csrf
                <input type="hidden" id="config_window_id" name="window_id">
                <div class="form-group">
                    <label for="service_type">Service Type</label>
                    <select id="service_type" name="service_type" class="form-control">
                        <option value="all">All Services</option>
                        <option value="registration">Registration Only</option>
                        <option value="inquiry">Inquiry Only</option>
                        <option value="updating">Updating Only</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="priority">Priority Level</label>
                    <select id="priority" name="priority" class="form-control">
                        <option value="normal">Normal</option>
                        <option value="priority">Priority</option>
                        <option value="express">Express</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="max_queue">Max Queue Length</label>
                    <input type="number" id="max_queue" name="max_queue" class="form-control" value="50" min="1" max="999">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="closeModal('configureWindowModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .windows-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .windows-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .window-card {
        background: white;
        border-radius: 16px;
        border: 2px solid var(--gray-200);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .window-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 56, 168, 0.1);
        border-color: var(--psa-blue);
    }
    
    .window-header {
        padding: 15px;
        background: linear-gradient(135deg, var(--psa-blue), var(--psa-red));
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .window-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .window-body {
        padding: 15px;
    }
    
    .window-operator {
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .operator-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .operator-avatar {
        width: 40px;
        height: 40px;
        background: var(--psa-blue-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--psa-blue);
        font-weight: 600;
        font-size: 1rem;
    }
    
    .operator-details {
        flex: 1;
    }
    
    .operator-details strong {
        display: block;
        color: var(--gray-800);
        margin-bottom: 2px;
    }
    
    .operator-status {
        font-size: 0.8rem;
        color: var(--success);
    }
    
    .operator-placeholder {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--gray-400);
        padding: 8px;
    }
    
    .operator-placeholder svg {
        width: 24px;
        height: 24px;
    }
    
    .window-stats {
        margin-bottom: 15px;
    }
    
    .stat-row {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
    }
    
    .window-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }
    
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255,255,255,0.3);
        transition: .2s;
        border-radius: 24px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .2s;
        border-radius: 50%;
    }
    
    input:checked + .slider {
        background-color: var(--psa-yellow);
    }
    
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    
    @media (max-width: 768px) {
        .windows-summary {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let currentWindow = null;
    
    document.querySelectorAll('.window-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const windowNum = this.dataset.window;
            const isActive = this.checked;
            
            showLoading();
            // Simulate API call
            setTimeout(() => {
                hideLoading();
                Swal.fire({
                    title: isActive ? 'Window Activated' : 'Window Deactivated',
                    text: `Window ${windowNum} has been ${isActive ? 'activated' : 'deactivated'}.`,
                    icon: 'success',
                    confirmButtonColor: '#0038A8',
                    timer: 2000
                });
            }, 1000);
        });
    });
    
    function assignOperator(windowNum) {
        currentWindow = windowNum;
        document.getElementById('modalWindowNum').textContent = windowNum;
        document.getElementById('window_id').value = windowNum;
        openModal('assignOperatorModal');
    }
    
    function configureWindow(windowNum) {
        currentWindow = windowNum;
        document.getElementById('configWindowNum').textContent = windowNum;
        document.getElementById('config_window_id').value = windowNum;
        openModal('configureWindowModal');
    }
    
    function viewWindowStats(windowNum) {
        Swal.fire({
            title: `Window ${windowNum} Statistics`,
            html: `
                <div style="text-align: left">
                    <p><strong>Today's Served:</strong> ${Math.floor(Math.random() * 50) + 15}</p>
                    <p><strong>Avg. Wait Time:</strong> ${Math.floor(Math.random() * 10) + 3} min</p>
                    <p><strong>Total Served (Week):</strong> ${Math.floor(Math.random() * 200) + 100}</p>
                    <p><strong>Current Queue:</strong> ${Math.floor(Math.random() * 10)}</p>
                </div>
            `,
            confirmButtonColor: '#0038A8'
        });
    }
    
    document.getElementById('assignOperatorForm').addEventListener('submit', function(e) {
        e.preventDefault();
        closeModal('assignOperatorModal');
        showLoading();
        
        setTimeout(() => {
            hideLoading();
            Swal.fire({
                title: 'Operator Assigned',
                text: `Operator has been assigned to Window ${currentWindow}`,
                icon: 'success',
                confirmButtonColor: '#0038A8'
            }).then(() => {
                location.reload();
            });
        }, 1500);
    });
    
    document.getElementById('configureWindowForm').addEventListener('submit', function(e) {
        e.preventDefault();
        closeModal('configureWindowModal');
        showLoading();
        
        setTimeout(() => {
            hideLoading();
            Swal.fire({
                title: 'Configuration Saved',
                text: `Window ${currentWindow} configuration has been updated.`,
                icon: 'success',
                confirmButtonColor: '#0038A8'
            });
        }, 1500);
    });
    
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('show');
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }
</script>
@endpush
@endsection