@component('mail::message')

@include('eshop::customer.emails.order.partials.logo')

<div style="font-size: 20pt; margin-bottom: 1rem;">{{ __("Order") . ' #' . $cart->id }}</div>

<div style="font-size: 1.25rem; margin-bottom: 1rem">{{ __("We received your order!") }}</div>

@if(filled($notesToCustomer))
<div style="margin-bottom: 1rem">{{ $notesToCustomer }}</div>
@endif

@include('eshop::customer.emails.order.partials.shipping-address')

@include('eshop::customer.emails.order.partials.items')

@component('mail::button', ['color' => 'success', 'url' => URL::signedRoute('order-tracking.show', [app()->getLocale(), $cart])])
    {{ __('View you order') }}
@endcomponent

@if(filled($cart->details))
<div style="margin-bottom: 1rem">
    <div style="font-size: 0.8rem; color: gray">{{ __('eshop::lang.comments') }}</div>
    <div>{{ $cart->details }}</div>
</div>
@endif

{{ __("Thank you for your order.") }}<br>
{{ config('app.name') }}
@endcomponent
