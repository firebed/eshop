@component('mail::message')

<div style="font-size: 1.25rem; margin-bottom: 1rem; text-align: center">{{ __("eshop::cart.events.abandoned-email-title") }} {{ __("eshop::cart.events.abandoned-email-help") }}</div>

@php($phones = __("company.phone"))
<div style="font-size: 1.25rem; margin-bottom: 1rem; text-align: center">
    {{ __("Phone numbers") }}<br>
    {!! implode("<br>", $phones) !!}
</div>

@component('mail::button', ['url' => URL::signedRoute('order-abandonment.show', [app()->getLocale(), $cart, $event])])
    Συνέχιση παραγγελίας
@endcomponent

@include('eshop::customer.emails.order.partials.items')

{{ config('app.name') }}
<img src="{{ URL::signedRoute('order-abandonment.track', [app()->getLocale(), $cart, $event]) }}" width="1" height="1" alt=""/>
@endcomponent
