@if ($paginator->hasPages())
    <nav>
        <ul class="pagination mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="btn btn-sm btn-outline-light text-dark border-0 disabled" aria-hidden="true"><em class="fa fa-chevron-left small text-secondary"></em></span>
                </li>
            @else
                <li class="page-item">
                    <a class="btn btn-sm btn-outline-light text-dark border-0" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"><em class="fa fa-chevron-left small text-secondary"></em></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="btn btn-sm btn-outline-light text-dark border-0 disabled">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page === $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="btn btn-sm btn-outline-light text-dark border-0 text-pink-500 fw-bold">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="btn btn-sm btn-outline-light text-dark border-0 shadow-none" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="btn btn-sm btn-outline-light text-dark border-0" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')"><em class="fa fa-chevron-right small text-secondary"></em></a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="btn btn-sm btn-outline-light text-dark border-0 disabled" aria-hidden="true"><em class="fa fa-chevron-right small text-secondary"></em></span>
                </li>
            @endif
        </ul>
    </nav>
@endif
