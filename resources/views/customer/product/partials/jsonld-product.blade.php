<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ stripslashes($product->trademark) }}",

  @if($product->image && $src = $product->image->url())
  "image": "{{ $src }}",
  @endif

  @if(filled($product->description))
  "description": "{{ stripslashes(strip_tags($product->description)) }}",
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

  "offers": {
    "@type": "Offer",
    "url": "{{ productRoute($product) }}",
    "priceCurrency": "{{ config('app.currency') }}",
    "price": "{{ number_format($product->netValue, 2) }}",
    @if($product->canBeBought())
        "availability": "http://schema.org/InStock",
    @else
        "availability": "http://schema.org/OutOfStock",
    @endif
    "itemCondition": "http://schema.org/NewCondition"
  }
}
</script>