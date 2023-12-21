<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>
        @page {
            /*margin: 1cm;*/
        }

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

        #items {
            border: 1px solid lightgray;
        }

        #items thead th {
            border-bottom: 1px solid lightgray;
        }

        #items th, #items td {
            border-right: 1px solid lightgray;
            padding: 2px 5px;
        }

        #items th:first-child, #items td:first-child {
            border-left: 1px solid lightgray;
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

        .mt-3 {
            margin-top: 1rem;
        }
    </style>
    <title>{{ __('Invoice') }} #{{ $invoice->number }}</title>
</head>
<body>

<div>
    <table>
        <tr>
            <td style="width: 30%">
                <img src="{{ eshop('logo.path') }}" height="{{ eshop('logo.height') }}" alt="">
            </td>

            <td style="width: 20%; text-align: center">
                @if(filled($qrCode))
                    <img src="data:image/png;base64, {!! $qrCode !!}">
                @endif
            </td>

            <td style="width: 50%" class="text-end">
                <div class="fw-bold">{{ __("company.name") }}</div>
                <div>{{ __("Vat number") }}: {{ __("company.vat") }} • {{ __("Tax authority") }}: {{ __("company.tax_office") }}</div>
                @if(eshop('gemi'))
                    <div>{{ __("REGISTRATION NUMBER") }}: {{ eshop('gemi') }}</div>
                @endif
                <div>{{ __('company.job') }}</div>
                <div>{{ __("company.address") }} • {{ __("TEL") }}: {{ __("company.phone")[0] }}</div>
                <div><a href="{{ __('company.website') }}">{{ __('company.website') }}</a> • {{ __("company.email") }}</div>
            </td>
        </tr>
    </table>
</div>

<div class="mt-3">
    <table class="table-bordered">
        <thead>
        <tr>
            <th>{{ __("Invoice type") }}</th>
            <th style="width: 15%;">{{ __("Series") }}</th>
            <th style="width: 15%;">{{ __("Number") }}</th>
            <th style="width: 20%;">{{ __("Date") }}</th>
            <th style="width: 15%;">{{ __("Time") }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-center">{{ $invoice->type->label() }}</td>
            <td class="text-center">{{ $invoice->row }}</td>
            <td class="text-center">{{ $invoice->number }}</td>
            <td class="text-center">{{ $invoice->published_at->format('d/m/Y') }}</td>
            <td class="text-center">{{ $invoice->published_at->format('H:i') }}</td>
        </tr>
        </tbody>
    </table>
</div>

<div class="mt-3" style="font-size: 11px">
    <table>
        <tr>
            <td style="border: 1px solid lightgray">
                <table class="table-dense">
                    <tr>
                        <th colspan="2" style="padding: 3px 0 !important; vertical-align: middle">{{ __("Client details") }}</th>
                    </tr>
                    <tr>
                        <td style="width: 25%;">{{ __("ID") }}:</td>
                        <td>{{ $invoice->client->id }}</td>
                    </tr>

                    <tr>
                        <td>{{ __("Company name") }}:</td>
                        <td>{{ $invoice->client->name }}</td>
                    </tr>

                    @if(in_array($invoice->type, [\Eshop\Models\Invoice\InvoiceType::PSL]))
                        <tr>
                            <td>{{ __("Phone") }}:</td>
                            <td>{{ $invoice->client->vat_number }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ __("VAT/IRS") }}:</td>
                            <td>{{ $invoice->client->vat_number }} / {{ $invoice->client->tax_authority }}</td>
                        </tr>
                    @endif

                    <tr>
                        <td>{{ __("Job") }}:</td>
                        <td style="white-space: nowrap; overflow: hidden;">{{ $invoice->client->job }}</td>
                    </tr>

                    <tr>
                        <td>{{ __("Address") }}:</td>
                        <td>{{ $invoice->client->street }} {{ $invoice->client->street_number }}</td>
                    </tr>

                    <tr>
                        <td>{{ __("City/ZIP Code") }}:</td>
                        <td>{{ $invoice->client->city }}, {{ $invoice->client->postcode }}</td>
                    </tr>
                </table>
            </td>

            <td style="border: 1px solid lightgray">
                <table class="table-dense">
                    <tr>
                        <td style="width: 40%;">{{ __("Relative invoice") }}:</td>
                        <td>{{ $invoice->relative_document }}</td>
                    </tr>

                    <tr>
                        <td>{{ __("Payment method") }}:</td>
                        <td>{{ $invoice->payment_method->label() }}</td>
                    </tr>

                    <tr>
                        <td>{{ __("Transaction purpose") }}:</td>
                        <td>{{ __("SALE") }}</td>
                    </tr>

                    <tr>
                        <td>{{ __("Shipping place") }}:</td>
                        <td>{{ __("HQ") }}</td>
                    </tr>

                    <tr>
                        <td>{{ __("Shipping destination") }}:</td>
                        <td>{{ $invoice->client->address }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="mt-3" style="break-inside: avoid">
    <table id="items">
        <thead>
        <tr>
            <th style="width: 13%;" class="text-start">{{ __("Code") }}</th>
            <th class="text-start">{{ __("Description") }}</th>
            <th style="width: 8%;" class="text-center">{{ __("UoM") }}</th>
            <th style="width: 9%;" class="text-end">{{ __("Qty") }}.</th>
            <th style="width: 9%;" class="text-end">{{ __("Price") }}</th>
            <th style="width: 8%;" class="text-end">{{ __("Disc") }}.</th>
            <th style="width: 7%;" class="text-end">{{ __("Tax") }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach($items as $row)
            <tr>
                <td style="white-space: nowrap; overflow: hidden">{{ $row->code }}</td>
                <td style="white-space: nowrap; overflow: hidden">{{ $row->description }}</td>
                <td class="text-center">{{ __($row->unit->abbr()) }}</td>
                <td class="text-end">{{ format_number($row->quantity) }}</td>
                <td class="text-end">{{ format_number($row->price, 2) }}</td>
                <td class="text-end">{{ format_percent($row->discount) }}</td>
                <td class="text-end">{{ format_percent($row->vat_percent) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-3" style="break-inside: avoid">
    <table style="font-size: 11px">
        <tr>
            <td style="width: 20%;">
                <table style="border: 1px solid lightgray">
                    <tr>
                        <th>{{ __("Quantities") }}</th>
                    </tr>
                    @foreach($units as $unit => $group)
                        <tr>
                            <td>
                                {{ $group->sum('quantity') }} {{ \Eshop\Models\Invoice\UnitMeasurement::from($unit)->abbr() }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </td>

            <td style="width: 20%;">
                <table style="border: 1px solid lightgray">
                    <tr>
                        <th>{{ __("Balance") }}</th>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td>{{ __("Prev") }}.:</td>
                                    <td class="text-end">{{ format_currency(0) }}</td>
                                </tr>

                                <tr>
                                    <td>{{ __("Next") }}:</td>
                                    <td class="text-end">{{ format_currency(0) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>

            <td>
                <table style="border: 1px solid lightgray">
                    <tr>
                        <th colspan="3">{{ __("Tax analysis") }}</th>
                    </tr>
                    <tr>
                        <th class="text-end">{{ __("Amount") }}</th>
                        <th class="text-end">%</th>
                        <th class="text-end">{{ __("Tax") }}</th>
                    </tr>
                    @foreach($vats as $vat => $row)
                        <tr>
                            <td class="text-end">{{ format_number($row['total_net_value']) }}</td>
                            <td class="text-end">{{ format_percent($vat) }}</td>
                            <td class="text-end">{{ format_number($row['total_vat_amount']) }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>

            <td>
                <table style="border: 1px solid #e3e2e2;">
                    <tr>
                        <th class="text-start fw-normal" style="width: 65%">{{ __("TOTAL VALUE") }}</th>
                        <td class="text-end" style="padding-right: 5px">{{ format_number($total_value, 2) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start fw-normal">{{ __("TOTAL DISCOUNT") }}</th>
                        <td class="text-end" style="padding-right: 5px">{{ format_number($discount_amount, 2) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start fw-normal">{{ __("NET VALUE") }}</th>
                        <td class="text-end" style="padding-right: 5px">{{ format_number($total_net_value, 2) }}</td>
                    </tr>

                    @if($total_extra_value > 0)
                        <tr>
                            <th class="text-start fw-normal">{{ __("CHARGES") }}</th>
                            <td class="text-end" style="padding-right: 5px">({{ format_number($total_extra_value, 2) }})</td>
                        </tr>
                    @endif

                    <tr>
                        <th class="text-start fw-normal">{{ __("TAX TOTAL") }}</th>
                        <td class="text-end" style="padding-right: 5px">{{ format_number($total_vat_amount, 2) }}</td>
                    </tr>

                    <tr>
                        <th class="text-start" style="border-top: 1px solid lightgray; padding-top: 5px; padding-bottom: 5px">{{ __("GROSS TOTAL") }}</th>
                        <td class="text-end fw-bold" style="border-top: 1px solid lightgray; padding-top: 5px; padding-bottom: 5px; padding-right: 5px">{{ format_number($invoice->total, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
