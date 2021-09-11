@props([
    'lowPrice',
    'highPrice',
    'priceCurrency' => config('app.currency'),
    'name' => null,
    'availability'
])

"offers": {
    "@type": "AggregateOffer",
    "lowPrice": {{ number_format($lowPrice, 2) }},
    "highPrice": {{ number_format($highPrice, 2) }},
    "priceCurrency": "{{ $priceCurrency }}",
    "availability": "{{ $availability }}"
}