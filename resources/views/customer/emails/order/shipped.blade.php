@component('mail::message')
<div style="font-size: 20pt; margin-bottom: 1rem;">{{ __("Order") . ' #' . $cart->id }}</div>

<div style="margin-bottom: 1rem">{{ __("Your order has been shipped!") }}</div>

@include('eshop::customer.emails.order.partials.shipping-address')

@include('eshop::customer.emails.order.partials.items')

<table style="width: 100%; table-layout: fixed;">
<tr>
<td style="text-align: center">
@component('mail::button', ['color' => 'success', 'url' => route('account.orders.show', [app()->getLocale(), $cart])])
    {{ __('See you order') }}
@endcomponent
</td>

<td style="text-align: center">
@component('mail::button', ['color' => 'success', 'url' => route('account.orders.show', [app()->getLocale(), $cart])])
    {{ __('See you order') }}
@endcomponent
</td>
</tr>
</table>

@if($notesToCustomer)
<div style="margin-bottom: 1rem">
    <div>{{ __('eshop::lang.comments') }}</div>
    <div>{{ $notesToCustomer }}</div>
</div>
@endif

{{ __("Thank you for your order.") }}<br>
{{ config('app.name') }}
@endcomponent
