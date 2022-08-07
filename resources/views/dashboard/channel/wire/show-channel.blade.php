<div class="col-12 p-4 d-grid gap-3">
    <div class="row">
        <div class="col-3 overflow-auto" style="height: calc(100vh - 6.5rem)">
            <div class="d-grid gap-3">
                <x-bs::card>
                    <x-bs::card.header class="d-flex justify-content-between">
                        <span>Κατηγορίες</span>
                        <a href="#" class="small text-decoration-none text-danger" wire:click.prevent="resetSelectedCategories">
                            <em class="fa fa-times"></em>
                        </a>
                    </x-bs::card.header>

                    <x-bs::card.body class="overflow-auto scrollbar" style="max-height: 350px">
                        @foreach($categories as $category)
                            <x-bs::input.checkbox wire:key="c-{{ $category->id }}" wire:model="selectedCategories" id="c-{{ $category->id }}" value="{{ $category->id }}">{{ $category->name }} ({{ $category->products_count }})</x-bs::input.checkbox>
                        @endforeach
                    </x-bs::card.body>
                </x-bs::card>

                <x-bs::card>
                    <x-bs::card.header class="d-flex justify-content-between">
                        <span>Κατασκευαστές</span>
                        <a href="#" class="small text-decoration-none text-danger" wire:click.prevent="resetSelectedManufacturers">
                            <em class="fas fa-times"></em>
                        </a>
                    </x-bs::card.header>

                    <x-bs::card.body class="overflow-auto scrollbar" style="max-height: 350px">
                        @foreach($manufacturers as $manufacturer)
                            <x-bs::input.checkbox wire:key="c-{{ $manufacturer->id }}" wire:model="selectedManufacturers" id="c-{{ $manufacturer->id }}" value="{{ $manufacturer->id }}">{{ $manufacturer->name }} ({{ $manufacturer->products_count }})</x-bs::input.checkbox>
                        @endforeach
                    </x-bs::card.body>
                </x-bs::card>
            </div>
        </div>

        <div class="col-6">
            <x-bs::card wire:loading.class="opacity-50">
                <x-bs::card.header>Προϊόντα στο {{ $channel->name }} ({{ $productsCount }})</x-bs::card.header>

                <div class="overflow-auto scrollbar" style="height: calc(100vh - 12rem)">
                    @foreach($products as $product)
                        <div wire:key="p-{{ $product->id }}" class="p-3 d-flex gap-2 border-bottom">
                            @if($product->image)
                                <div class="ratio ratio-1x1" style="width: 48px">
                                    <img src="{{ $product->image->url('sm') }}" alt="" class="w-auto h-auto mh-100 mw-100">
                                </div>
                            @endif

                            <div class="vstack">
                                @if($product->isVariant())
                                    <a href="{{ route('variants.edit', $product) }}" class="text-decoration-none">{{ $product->parent->name . ' ' .  $product->option_values }}</a>
                                @else
                                    <a href="{{ route('products.edit', $product) }}" class="text-decoration-none">{{ $product->name }}</a>
                                @endif
                                <div class="d-flex gap-2 small text-secondary">
                                    <div>{{ format_currency($product->net_value) }}</div>

                                    @if(filled($product->barcode))
                                        <div class="border-end"></div>
                                        <div>{{ $product->barcode }}</div>
                                    @endif

                                    @if($product->manufacturer)
                                        <div class="border-end"></div>
                                        <div>{{ $product->manufacturer?->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-bs::card>

            @if($products->hasPages())
                <div class="mt-3">
                    <x-eshop::wire-pagination :paginator="$products"/>
                </div>
            @endif
        </div>

        <div class="col-3 overflow-auto" style="height: calc(100vh - 6.5rem)">
            <x-bs::card>
                <x-bs::card.header>Επιλογές</x-bs::card.header>
                
                <x-bs::card.body class="small d-grid gap-1">
                    <div><strong>{{ $keysCount }}</strong> ενεργά προϊόντα</div>
                    @php
                        $b = $keys->whereNull('barcode')->count();
                        $m = $keys->whereNull('mpn')->count();
                        $s = $keys->whereNull('sku')->count();
                        $p = $keys->filter(fn($p) => $p->net_value <= 0)->count();
                        $u = $keys->reject(fn($p) => $p->canBeBought())->count();
                        $i = $keys->where('visible', false)->count();
                        $w = $keys->filter(fn($p) => $p->weight <= 0)->count();
                    @endphp
                    <div class="ps-3"><strong class="text-success">{{ $keys->count() - $missingImages - $b - $m - $u - $p - $i - $w}}</strong> εμφανίζονται στο {{ $channel->name }}</div>
                    <div class="ps-3"><strong class="text-danger">{{ $u }}</strong> μη διαθέσιμα</div>
                    <div class="ps-3"><strong class="text-danger">{{ $i }}</strong> μη ορατά</div>
                    <div class="ps-3"><strong class="text-danger">{{ $missingImages }}</strong> χωρίς εικόνες</div>
                    <div class="ps-3"><strong class="text-danger">{{ $p }}</strong> με μηδενική τιμή</div>
                    <div class="ps-3"><strong class="text-danger">{{ $s }}</strong> χωρίς SKU</div>
                    <div class="ps-3"><strong class="text-danger">{{ $m }}</strong> χωρίς MPN</div>
                    <div class="ps-3"><strong class="text-danger">{{ $b }}</strong> χωρίς barcode</div>
                    <div class="ps-3"><strong class="text-danger">{{ $w }}</strong> χωρίς βάρος</div>
                    
                    <div class="mt-3"><strong class="text-secondary">{{ $inactive }}</strong> ανενεργά προϊόντα</div>
                </x-bs::card.body>
            </x-bs::card>
        </div>
    </div>
</div>
