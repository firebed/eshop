{
"@context": "https://schema.org/",
"@type": "Product",
"name": "{{ addslashes($product->seo->title) }}",

@if(filled($product->seo->description))
    "description": "{{ addslashes($product->seo->description) }}",
@endif

@if($product->image && $src = $product->image->url())
    "image": "{{ $src }}",
@endif

@if(filled($product->sku))
    "sku": "{{ $product->sku }}",
@endif

@if($product->manufacturer)
    "brand": {
    "@type": "Brand",
    "name": "{{ $product->manufacturer->name }}"
    },
@endif

{!! $slot !!}
}