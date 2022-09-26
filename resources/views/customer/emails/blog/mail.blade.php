@component('mail::message')

# {{ $blog->title }}

<h2 style="color: black; font-weight: normal">{!! $blog->description !!}</h2>

@component('mail::button', ['url' => URL::signedRoute('blogs.click', [app()->getLocale(), $blog->slug])])
    Δείτε όλο το άρθρο...
@endcomponent

{{ config('app.name') }}
<img src="{{ URL::signedRoute('blogs.track', [app()->getLocale(), $blog->slug]) }}" width="1" height="1" alt=""/>
@endcomponent
