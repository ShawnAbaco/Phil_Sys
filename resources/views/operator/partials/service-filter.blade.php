@php
    $serviceOptions = [
        'all' => 'All Services',
        'Status Inquiry' => 'Status Inquiry',
        'NID Registration' => 'Registration',
        'Updating' => 'Updating'
    ];
    $currentService = request()->get('service', 'all');
@endphp

<div class="service-filter-selector">
    <label for="transactionServiceFilter">Filter by:</label>
    <select id="transactionServiceFilter" name="service" class="service-filter-dropdown">
        @foreach($serviceOptions as $value => $label)
            <option value="{{ $value }}" {{ $currentService == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

<style>
.service-filter-selector {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--gray-600);
    margin-right: 10px;
}

.service-filter-dropdown {
    padding: 5px 10px;
    border: 1px solid var(--gray-200);
    border-radius: 6px;
    background: white;
    font-size: 13px;
    color: var(--gray-700);
    cursor: pointer;
    outline: none;
    transition: all 0.2s ease;
    min-width: 140px;
}

.service-filter-dropdown:hover {
    border-color: var(--psa-blue);
}

.service-filter-dropdown:focus {
    border-color: var(--psa-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}
</style>