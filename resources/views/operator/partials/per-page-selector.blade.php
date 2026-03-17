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