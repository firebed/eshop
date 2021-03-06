@props([
'paginator',
'onEachSide' => 1
])

<div class="d-flex justify-content-between align-items-center px-2">
    @if($paginator instanceof \Illuminate\Support\Collection)
        <div class="small">@choice('eshop::pagination.showing_all', $paginator->count(), ['total' => $paginator->count()])</div>
    @else
        <div class="small">{{ __('eshop::pagination.showing', ['first' => $paginator->firstItem() ?? 0, 'last' => $paginator->lastItem() ?? 0, 'total' => $paginator->total()]) }}</div>

        {{ $paginator->onEachSide($onEachSide)->links('bs::pagination.livewire-paginator') }}
    @endif
</div>
