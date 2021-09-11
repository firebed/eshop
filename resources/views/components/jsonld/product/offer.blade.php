@props([
    'price',
    'priceCurrency' => config('app.currency'),
    'name' => null,
    'availability'
])

"offers": {
    "@type": "Offer",
    "price": {{ number_format($price, 2) }},
    "priceCurrency": "{{ $priceCurrency }}",
    "availability": "{{ $availability }}"
}