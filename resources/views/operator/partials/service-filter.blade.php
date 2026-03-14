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