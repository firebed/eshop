@extends('eshop::customer.layouts.master', ['title' => $blog->title])

@push('meta')
    <meta name="description" content="{{ $blog->description }}">
    <link rel="canonical" href="{{ $canonical = route('blogs.show', [$locale, $blog->slug]) }}">
    <meta name='robots' content='index, follow'/>

    <script type="application/ld+json">{!! schema()->webPage($blog->title, $blog->description) !!}</script>

    <meta property="og:title" content="{{ $blog->title }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ $blog->description }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonical }}">
    @if($blog->image)
        <meta property="og:image" content="{{ $blog->image->url() }}">
    @endif
    <meta name="twitter:card" content="summary"/>
@endpush

@section('main')
    <main class="container-fluid my-4">
        <article class="container-xxl">
            <h1 class="text-dark fs-2">{{ $blog->title }}</h1>

            <p class="small text-secondary">
                {{ $blog->created_at->isoFormat('lL') }}
            </p>

            <p>{{ $blog->description }}</p>
            
            @if($blog->image)
                <div class="my-3">
                    <img src="{{ $blog->image->url() }}" alt="{{ $blog->title }}" class="img-fluid">
                </div>
            @endif

            {!! $blog->content !!}
        </article>
    </main>
@endsection
