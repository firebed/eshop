@component('mail::message')
<div style="font-size: 20pt; margin-bottom: 1rem;">{{ __("Order") . ' #' . $cart->id }}</div>

<div style="font-size: 1.25rem; margin-bottom: 1rem">{{ __("We received your order!") }}</div>

@include('eshop::customer.emails.order.partials.shipping-address')

@include('eshop::customer.emails.order.partials.items')

@component('mail::button', ['color' => 'success', 'url' => route('account.orders.show', [app()->getLocale(), $cart])])
    {{ __('See you order') }}
@endcomponent

@if($notesToCustomer)
    <div style="margin-bottom: 1rem">
        {{ $notesToCustomer }}
    </div>
@endif

{{ __("Thank you for your order.") }}<br>
{{ config('app.name') }}
@endcomponent
