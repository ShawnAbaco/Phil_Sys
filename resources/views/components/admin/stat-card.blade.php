@props([
    'icon' => null,
    'label' => '',
    'value' => '0',
    'change' => null,
    'changeType' => 'positive',
    'color' => 'linear-gradient(135deg, var(--psa-blue), var(--psa-red))'
])

<div class="stat-card">
    <div class="stat-icon" style="background: {{ $color }}">
        {!! $icon !!}
    </div>
    <div class="stat-content">
        <span class="stat-label">{{ $label }}</span>
        <span class="stat-value">{{ $value }}</span>
        @if($change)
            <div class="stat-change {{ $changeType }}">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    @if($changeType === 'positive')
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                    @else
                        <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd" />
                    @endif
                </svg>
                {{ $change }}
            </div>
        @endif
    </div>
</div>