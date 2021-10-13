<div id="main-carousel" class="carousel carousel-dark slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        @foreach($slides as $slide)
            <div @class(["carousel-item", "active" => $loop->first])>
                @if($slide->link) <a href="{{ $slide->link }}"> @endif
                    <div class="ratio ratio-16x9">
                        <img loading="lazy" src="{{ $slide->image->url() }}" alt="...">
                    </div>
                    @if($slide->link) </a> @endif
            </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#main-carousel" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </a>
    <a class="carousel-control-next" href="#main-carousel" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </a>
</div>
