@component('mail::message')
<div style="font-size: 20pt; margin-bottom: 1rem;">{{ __("Order") . ' #' . $cart->id }}</div>

<div style="font-size: 1.25rem; margin-bottom: 1rem">{{ __("We received your order!") }}</div>

@include('emails.order.partials.shipping-address')

@include('emails.order.partials.items')

@component('mail::button', ['color' => 'success', 'url' => route('account.orders.show', [app()->getLocale(), $cart])])
    {{ __('See you order') }}
@endcomponent

@if($notesToCustomer)
<div style="margin-bottom: 1rem">
    <div>{{ __('eshop::lang.comments') }}</div>
    <div>{{ $notesToCustomer }}</div>
</div>
@endif

{{ __("Thank you for your order.") }}<br>
{{ config('app.name') }}
@endcomponent
