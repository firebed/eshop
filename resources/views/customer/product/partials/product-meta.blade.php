@push('meta')
    <link rel="canonical" href="{{ productRoute($product, $category) }}">
    @foreach(array_keys(eshop('locales')) as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ productRoute($product, $category, $locale) }}"/>
    @endforeach

    <script type="application/ld+json">{!! schema()->breadcrumb($category, $product) !!}</script>
    <script type="application/ld+json">{!! schema()->product($product) !!}</script>
    <script type="application/ld+json">{!! schema()->webPage($title, $description) !!}</script>

    @if(!empty($description))
        <meta name="description" content="{{ $description }}">
    @endif

    <meta property="og:title" content="{{ $title }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    @if(!empty($description))
        <meta property="og:description" content="{{ $description }}">
    @endif
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ productRoute($product, $category) }}">
    @if($product->image)
        <meta property="og:image" content="{{ $product->image->url() }}">
    @endif

    <meta name="twitter:card" content="product"/>
    <meta name="twitter:title" content="{{ $title }}">
    @if($product->image)
        <meta name="twitter:image" content="{{ $product->image->url() }}">
    @endif
    @if(!empty($description))
        <meta name="twitter:description" content="{{ $description }}">
    @endif

    <meta name="robots" content="index, follow"/>
@endpush