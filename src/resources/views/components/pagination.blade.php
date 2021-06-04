@props([
    'paginator',
    'onEachSide' => 1
])

<div class="d-flex justify-content-between align-items-center px-2">
    <div class="small">{{ __('pagination.showing', ['first' => $paginator->firstItem() ?? 0, 'last' => $paginator->lastItem() ?? 0, 'total' => $paginator->total()]) }}</div>

    {{ $paginator->onEachSide($onEachSide)->links() }}
</div>
