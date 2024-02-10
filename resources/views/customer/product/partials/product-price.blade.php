@php    
    $price = $variant?->getNetValueForUser(auth()->user());
    $min = $this->variants->min(fn($v) => $v->getNetValueForUser(auth()->user()));
    $max = $this->variants->max(fn($v) => $v->getNetValueForUser(auth()->user()));
@endphp

<div>
@if($price)
    <div class="fs-3">{{ format_currency($price) }}</div>
@else
    <div class="fs-3">
        {{ format_currency($min) }}

        @if($min !== $max)
            - {{ format_currency($max) }}
        @endif
    </div>
@endif
</div>