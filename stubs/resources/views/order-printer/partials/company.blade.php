<table>
    <tr>
        <td>
            <div style="font-size: 20px;">{{ config('app.name') }}</div>
            <div>{{ __('company.job') }}</div>
            <div><a href="{{ __('company.website') }}">{{ __('company.website') }}</a></div>
            <div>{{ __("company.email") }}</div>
        </td>
        <td class="text-end">
            <div>{{ __("company.name") }}</div>
            <div>{{ __("company.address") }}</div>
            <div>{{ __("company.phone")[0] }}</div>
            <div>{{ __("Vat number") }}: {{ __("company.vat") }} • {{ __("company.tax_office") }}</div>
        </td>
    </tr>
</table>
