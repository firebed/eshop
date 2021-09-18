@props([
    'value' => '',
    'error' => NULL,
    'min' => '0',
    'max' => '10000000000000',
    'currency' => config('eshop.currency_symbol'),
    'groupsSeparator' => config('eshop.group_separator'),
    'decimalSeparator' => config('eshop.decimal_separator'),
    'currencyPlacement' => config('eshop.currency_placement'),
    'signPlacement' => config('eshop.sign_placement'),
    'emptyInputBehavior' => 'zero'
])

<input type="text"
       autocomplete="off"
       x-data="{ value: {{ $value ?: 0 }} }"
       x-init="
        new AutoNumeric($el, value, {
            digitGroupSeparator           : '{{ $groupsSeparator }}',
            decimalCharacter              : '{{ $decimalSeparator }}',
            decimalCharacterAlternative   : '{{ $groupsSeparator }}',
            currencySymbol                : '{{ $currency }}',
            currencySymbolPlacement       : '{{ $currencyPlacement }}',
            negativePositiveSignPlacement : '{{ $signPlacement }}',
            minimumValue                  : '{{ $min }}',
            maximumValue                  : '{{ $max }}',
            modifyValueOnWheel            : false,
            emptyInputBehavior            : '{{ $emptyInputBehavior }}',
            watchExternalChanges          : true
        })
        $el.addEventListener('autoNumeric:rawValueModified', evt => value = evt.detail.newRawValue)
        "
        {{ $attributes->class(['form-control', 'is-invalid' => $error && $errors->has($error)]) }}>

@if($error)
    @error($error)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
@endif