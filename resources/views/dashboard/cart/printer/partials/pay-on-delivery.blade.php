<table class="mt-3">
    <tr>
        <td>&nbsp;</td>
        <td style="border: 1px solid #ccc">
            <h2 class="text-center" style="margin: 0; padding: 4px">
                {{ __("eshop::payment.".$cart->paymentMethod->name) }}: ({{ format_currency($cart->total) }})
            </h2>
        </td>
    </tr>
</table>
