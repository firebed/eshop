<div class="container-fluid py-3 d-none d-md-block">
    <div class="container-xxl">
        <nav aria-label="breadcrumb" class="small">
            <ol class="breadcrumb m-0">
                @foreach($items as $item)
                    <li class="breadcrumb-item">
                        @if($loop->last)
                            {{ $item['name'] }}
                        @else
                            <a href="{{ $item['url'] }}" class="text-decoration-none text-secondary">{{ $item['name'] }}</a>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>
</div>