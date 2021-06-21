<div class="container-fluid pt-3">
    <div class="container">
        <nav aria-label="breadcrumb" class="small">
            <ol class="breadcrumb">
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
