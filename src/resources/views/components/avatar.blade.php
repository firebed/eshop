@props(['size' => 60, 'url'])

<div {{ $attributes->merge(['class' => 'rounded rounded-circle']) }} {{ $attributes }}
style="width: {{ $size }}px;
    height: {{ $size }}px;
    background-repeat:no-repeat;
    background-size: cover;
    background-position: center;
    background-image: url('{!! $url !!}')"></div>
