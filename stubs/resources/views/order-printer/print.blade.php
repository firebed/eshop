<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    <style>
        body {
            font-family: DejaVu Sans, serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            table-layout: fixed;
            width: 100%;
        }

        th, td {
            padding: 2px;
            vertical-align: top;
        }

        table.table-dense th, table.table-dense td {
            padding: 0 !important;
        }

        #items td {
            font-size: 12px;
        }

        #items th, #items td {
            border: 1px solid lightgray;
            padding: 2px 5px;
        }

        table th {
            background-color: rgb(240, 240, 240);
        }

        table.table-bordered, table.table-bordered th, table.table-bordered td {
            border: 1px solid lightgray;
        }

        table.table-borderless, table.table-borderless th, table.table-borderless td {
            border: none;
        }

        .fw-normal {
            font-weight: normal;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-secondary {
            color: gray;
        }

        .number {
            text-align: right;
        }

        .mt-3 {
            margin-top: 1rem;
        }
    </style>
    <title>{{ __('Order') }} {{ $cart->id }}</title>
</head>
<body>
@include('order-printer.partials.company')

<div class="mt-3">
    <table>
        <tr>
            <td>
                @include('order-printer.partials.order-details')
            </td>
            <td>
                @include('order-printer.partials.shipping')
            </td>
        </tr>
    </table>
</div>

@includeWhen($cart->paymentMethod && $cart->paymentMethod->show_total_on_order_form, 'order-printer.partials.pay-on-delivery')

<hr class="mt-3">

<div style="page-break-inside: avoid">
    @include('order-printer.partials.products')
</div>

<div style="page-break-inside: avoid">
    @include('order-printer.partials.totals')
</div>

<h2 class="fw-normal text-secondary text-center mt-3">{{ __("Thank you for your order.") }}</h2>
<div class="fw-normal text-secondary text-center">{{ config('app.name') }}</div>

</body>
</html>
