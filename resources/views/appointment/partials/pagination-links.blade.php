@php
    $currentPage = $completedTransactions->currentPage();
    $lastPage = $completedTransactions->lastPage();
    
    // Calculate which 5-page block we're in (1-5, 6-10, 11-15, etc.)
    $blockSize = 5;
    $currentBlock = ceil($currentPage / $blockSize);
    
    // Calculate start and end of current block
    $blockStart = (($currentBlock - 1) * $blockSize) + 1;
    $blockEnd = min($blockStart + $blockSize - 1, $lastPage);
    
    // Calculate previous block start
    $prevBlockStart = max(1, $blockStart - $blockSize);
    
    // Calculate next block start
    $nextBlockStart = $blockStart + $blockSize;
@endphp

{{-- Previous Button (far left) --}}
@if ($completedTransactions->onFirstPage())
    <button class="pagination-nav-btn disabled" disabled>
        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
        Previous
    </button>
@else
    <a href="{{ $completedTransactions->previousPageUrl() }}" class="pagination-nav-btn">
        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
        Previous
    </a>
@endif

{{-- Center Group: Arrow + Page Numbers + Arrow --}}
<div class="pagination-center-group">
    {{-- Left Arrow for Previous Block --}}
    @if ($blockStart > 1)
        <a href="{{ $completedTransactions->url($prevBlockStart) }}" class="pagination-arrow" title="Previous 5 pages">
            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
        </a>
    @else
        <span class="pagination-arrow disabled">
            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
        </span>
    @endif

    {{-- Page Numbers - Show only current block (1-5, 6-10, etc.) --}}
    <div class="pagination-numbers">
        {{-- Show first page if not in first block --}}
        @if ($blockStart > 1)
            <a href="{{ $completedTransactions->url(1) }}" class="page-number">1</a>
            @if ($blockStart > 2)
                <span class="page-dots">...</span>
            @endif
        @endif

        {{-- Show current block pages (strictly 5 pages or less if near end) --}}
        @for ($i = $blockStart; $i <= $blockEnd; $i++)
            @if ($i == $currentPage)
                <span class="page-number active">{{ $i }}</span>
            @else
                <a href="{{ $completedTransactions->url($i) }}" class="page-number">{{ $i }}</a>
            @endif
        @endfor
        
        {{-- Show last page if not in last block --}}
        @if ($blockEnd < $lastPage)
            @if ($blockEnd < $lastPage - 1)
                <span class="page-dots">...</span>
            @endif
            <a href="{{ $completedTransactions->url($lastPage) }}" class="page-number">{{ $lastPage }}</a>
        @endif
    </div>

    {{-- Right Arrow for Next Block --}}
    @if ($blockEnd < $lastPage)
        <a href="{{ $completedTransactions->url($nextBlockStart) }}" class="pagination-arrow" title="Next 5 pages">
            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </a>
    @else
        <span class="pagination-arrow disabled">
            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </span>
    @endif
</div>

{{-- Next Button (far right) --}}
@if ($completedTransactions->hasMorePages())
    <a href="{{ $completedTransactions->nextPageUrl() }}" class="pagination-nav-btn">
        Next
        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    </a>
@else
    <button class="pagination-nav-btn disabled" disabled>
        Next
        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    </button>
@endif