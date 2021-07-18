@props([
    'value' => '',
    'error' => NULL,
    'min' => '-10000000000000',
    'max' => '10000000000000',
    'signPlacement' => config('intl.sign_placement'),
    'symbolPlacement' => config('intl.currency_placement'),
    'emptyInputBehavior' => 'null',
    'currencySymbol' => ''
])

<input type="text"
       autocomplete="off"
       x-data="{ value: {{ $value }} }"
       x-init="
        new AutoNumeric($el, value, {
            digitGroupSeparator           : '',
            negativePositiveSignPlacement : '{{ $signPlacement }}',
            decimalPlaces                 : '0',
            currencySymbol                : '{{ $currencySymbol }}',
            currencySymbolPlacement       : '{{ $symbolPlacement }}',
            minimumValue                  : '{{ $min }}',
            maximumValue                  : '{{ $max }}',
            modifyValueOnWheel            : false,
            emptyInputBehavior            : '{{ $emptyInputBehavior }}',
            watchExternalChanges: true
        })
        $el.addEventListener('autoNumeric:rawValueModified', evt => value = evt.detail.newRawValue)
        "
        {{ $attributes->class(['form-control', 'is-invalid' => $error && $errors->has($error)]) }}>

@if($error)
    @error($error)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
@endif