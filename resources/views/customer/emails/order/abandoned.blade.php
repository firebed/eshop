@component('mail::message')

@include('eshop::customer.emails.order.partials.logo')

<div style="font-size: 1.25rem; margin-bottom: 1rem">{{ __("eshop::cart.events.abandoned-email-title") }}</div>
<div style="margin-bottom: 1rem">{{ __("eshop::cart.events.abandoned-email-help") }}</div>

@php($phones = __("company.phone"))
<div style="margin-bottom: 1rem">
    {{ __("Phone numbers") }}<br>
    {!! implode("<br>", $phones) !!}
</div>

@include('eshop::customer.emails.order.partials.items')

@component('mail::button', ['color' => 'success', 'url' => URL::signedRoute('order-abandonment.show', [app()->getLocale(), $cart, $event])])
    {{ __('View you order') }}
@endcomponent

{{ config('app.name') }}
@endcomponent
