@component('mail::message')
<div style="font-size: 20pt">{{ __("Order id") . ' #' . $cart->id }}</div>
{{--# {{ __("Your order has been shipped.") }}--}}

{{ $notesToCustomer ?? '' }}

<table class="mb-3" style="width: 100%; table-layout: fixed; background-color: whitesmoke;">
    <tr>
        <td>@include('emails.partials.contact')</td>
        <td>@include('emails.partials.shipping-address')</td>
    </tr>
</table>

{{--@isset($cart->voucher)--}}
@component('mail::button', ['color' => 'success', 'url' => $cart->getVoucherUrl()])
    {{ __('Click for tracking') }}
@endcomponent
{{--@endisset--}}

@include('emails.partials.items')

{{ __("Thank you for your order.") }}<br>
{{ config('app.name') }}
@endcomponent
