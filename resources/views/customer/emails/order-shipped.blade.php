@component('mail::message')
<div style="font-size: 20pt">{{ __("Order") . ' #' . $cart->id }}</div>
# {{ __("Your order has been shipped.") }}

{{ $notesToCustomer ?? '' }}

<table class="mb-3" style="width: 100%; table-layout: fixed; background-color: whitesmoke;">
    <tr>
        <td>@include('eshop::customer.emails.partials.shipping-address')</td>
    </tr>
</table>

@component('mail::button', ['color' => 'success', 'url' => route('account.orders.show', [app()->getLocale(), $cart])])
    {{ __('See you order') }}
@endcomponent

@include('eshop::customer.emails.partials.items')

{{ __("Thank you for your order.") }}<br>
{{ config('app.name') }}
@endcomponent
