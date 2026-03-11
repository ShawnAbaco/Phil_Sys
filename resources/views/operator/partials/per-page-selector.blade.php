@php
    $perPageOptions = [10, 20, 50, 100];
    $currentPerPage = request()->get('per_page', 10);
@endphp

<div class="per-page-selector">
    <label for="perPage">Show</label>
    <select id="perPage" name="per_page" class="per-page-dropdown">
        @foreach($perPageOptions as $option)
            <option value="{{ $option }}" {{ $currentPerPage == $option ? 'selected' : '' }}>
                {{ $option }}
            </option>
        @endforeach
    </select>
    <span>entries</span>
</div>

<style>
.per-page-selector {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--gray-600);
    margin-right: 10px;
}

.per-page-dropdown {
    padding: 5px 10px;
    border: 1px solid var(--gray-200);
    border-radius: 6px;
    background: white;
    font-size: 13px;
    color: var(--gray-700);
    cursor: pointer;
    outline: none;
    transition: all 0.2s ease;
}

.per-page-dropdown:hover {
    border-color: var(--psa-blue);
}

.per-page-dropdown:focus {
    border-color: var(--psa-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}
</style>