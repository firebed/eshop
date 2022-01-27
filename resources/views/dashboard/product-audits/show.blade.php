<div class="table-responsive">
    <table class="table small table-borderless table-striped mb-0" style="table-layout: fixed">
        <tbody>
        @foreach(($audit->payload['translations'] ?? []) as $translation)
            <tr>
                <td class="text-muted" style="width: 15rem">{{ $translation['cluster'] }} ({{ $translation['locale'] }})</td>
                <td>
                    @if($translation['cluster'] === 'description')
                        <details>
                            <summary>Περιγραφή</summary>
                            {!! $translation['translation'] !!}
                        </details>
                    @else
                        {{ $translation['translation'] }}
                    @endif
                </td>
            </tr>
        @endforeach

        <tr>
            <td class="text-muted">Κατηγορία</td>
            <td>{{ $audit->payload['category'] }}</td>
        </tr>

        <tr>
            <td class="text-muted">Κατασκευαστής</td>
            <td>{{ $audit->payload['manufacturer'] }}</td>
        </tr>

        <tr>
            <td class="text-muted">Μονάδα Μέτρησης</td>
            <td>{{ __("eshop::unit." . $audit->payload['unit']) }}</td>
        </tr>
        
        @if($product->isVariant())
            <tr>
                <td class="text-muted">Παραλλαγή</td>
                <td>{{ collect($audit->payload['option_values'] ?? [])->mapWithKeys(static fn($v, $k) => ["$k: $v"])->implode(', ') }}
                </td>
            </tr>
        @else
            <tr>
                <td class="text-muted">Ιδιότητες</td>
                <td>
                    @foreach(($audit->payload['properties'] ?? []) as $property => $choices)
                        <div>{{ $property . ": " . implode(', ', $choices) }}</div>
                    @endforeach
                </td>
            </tr>
        @endif
        
        @if($product->has_variants)
            <tr>
                <td class="text-muted">Τύποι παραλλαγών</td>
                <td>{{ implode(', ', $audit->payload['variant_types'] ?? []) }}</td>
            </tr>

            <tr>
                <td class="text-muted">Εμφάνιση παραλλαγών</td>
                <td>{{ $audit->payload['variants_display'] ?? '' }}</td>
            </tr>

            <tr>
                <td class="text-muted">Προεπισκόπηση παραλλαγών</td>
                <td>{{ $audit->payload['preview_variants'] ? 'Ναι' : 'Όχι' }}</td>
            </tr>
        @endif

        <tr>
            <td class="text-muted">Τιμή</td>
            <td>{{ format_currency($audit->payload['price'] ?: 0) }}</td>
        </tr>

        <tr>
            <td class="text-muted">Τιμή σύγκρισης</td>
            <td>{{ format_currency($audit->payload['compare_price'] ?: 0) }}</td>
        </tr>

        <tr>
            <td class="text-muted">ΦΠΑ</td>
            <td>{{ format_percent($audit->payload['vat'] ?: 0) }}</td>
        </tr>

        <tr>
            <td class="text-muted">Έκπτωση</td>
            <td>{{ format_percent($audit->payload['discount'] ?: 0) }}</td>
        </tr>

        <tr>
            <td class="text-muted">SKU</td>
            <td>{{ $audit->payload['sku'] ?? '' }}</td>
        </tr>

        <tr>
            <td class="text-muted">MPN</td>
            <td>{{ $audit->payload['mpn'] ?? '' }}</td>
        </tr>

        <tr>
            <td class="text-muted">Barcode</td>
            <td>{{ $audit->payload['barcode'] ?? '' }}</td>
        </tr>

        <tr>
            <td class="text-muted">Φυσικό προϊόν</td>
            <td>
                @isset($audit->payload['is_physical'])
                    {{ $audit->payload['is_physical'] ? 'Ναι' : 'Όχι' }}
                @endisset
            </td>
        </tr>

        <tr>
            <td class="text-muted">Βάρος</td>
            <td>{{ format_weight($audit->payload['weight'] ?: 0) }}</td>
        </tr>

        <tr>
            <td class="text-muted">Απόθεμα</td>
            <td>{{ format_number($audit->payload['stock'] ?: 0) }}</td>
        </tr>

        <tr>
            <td class="text-muted">Ορατό</td>
            <td>{{ $audit->payload['visible'] ? 'Ναι' : 'Όχι' }}</td>
        </tr>

        <tr>
            <td class="text-muted">Διαθέσιμο</td>
            <td>{{ $audit->payload['available'] ? 'Ναι' : 'Όχι' }}</td>
        </tr>

        <tr>
            <td class="text-muted">Διαθέσιμο ελάχιστο</td>
            <td>{{ $audit->payload['available_gt'] ?? '' }}</td>
        </tr>

        <tr>
            <td class="text-muted">Εμφάνιση αποθέματος</td>
            <td>{{ $audit->payload['display_stock'] ? 'Ναι' : 'Όχι' }}</td>
        </tr>

        <tr>
            <td class="text-muted">Εμφάνιση αποθέματος max</td>
            <td>{{ $audit->payload['display_stock_lt'] ? 'Ναι' : 'Όχι' }}</td>
        </tr>

        <tr>
            <td class="text-muted">Θέση/Ράφι</td>
            <td>{{ $audit->payload['location'] ?? '' }}</td>
        </tr>

        <tr>
            <td class="text-muted">Slug</td>
            <td>{{ $audit->payload['slug'] ?? '' }}</td>
        </tr>
        
        @foreach(($audit->payload['seo'] ?? []) as $locale => $seo)
            <tr>
                <td class="text-muted">SEO Τίτλος ({{ $locale }})</td>
                <td>{{ $seo['title'] }}</td>
            </tr>
            
            <tr>
                <td class="text-muted">SEO Περιγραφή ({{ $locale }})</td>
                <td>
                    @if(filled($seo['description']))
                        <details>
                            <summary>Περιγραφή</summary>
                            {!! $seo['description'] !!}
                        </details>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>