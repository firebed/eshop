@props([
    'value' => '',
    'error' => NULL,
    'min' => '-10000000000000',
    'max' => '10000000000000',
    'groupsSeparator' => config('intl.group_separator'),
    'decimalSeparator' => config('intl.decimal_separator'),
    'symbolPlacement' => config('intl.currency_placement'),
    'signPlacement' => config('intl.sign_placement'),
    'decimalPadding' => 'floats',
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
            currencySymbol                : '%',
            currencySymbolPlacement       : '{{ $symbolPlacement }}',
            negativePositiveSignPlacement : '{{ $signPlacement }}',
            minimumValue                  : '{{ $min }}',
            maximumValue                  : '{{ $max }}',
            allowDecimalPadding           : '{{ $decimalPadding }}',
            rawValueDivisor               : 100,
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