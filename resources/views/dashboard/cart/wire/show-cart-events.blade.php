<div>
    <a wire:click.prefetch="$set('show', true)" role="button">Προβολή ιστορικού</a>

    <div wire:ignore.self
         x-data="{ show: @entangle('show'), offcanvas: null }"
         x-init="
            offcanvas = new bootstrap.Offcanvas($el)
            $watch('show', () => { if(show) offcanvas.show() })
            $el.addEventListener('hide.bs.offcanvas', () => show = false)            
         "
         class="offcanvas offcanvas-end" tabindex="-1" id="cart-events">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Ιστορικό</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            @foreach($events as $event)
                <div wire:key="event" class="d-flex mb-3 align-items-start">
                    <div class="pe-3">
                        @if($event->type === \Eshop\Models\Cart\CartEvent::INFO)
                            <em class="fas fa-exclamation-circle text-primary fs-5"></em>
                        @elseif($event->type === \Eshop\Models\Cart\CartEvent::SUCCESS)
                            <em class="fas fa-check-circle text-success fs-5"></em>
                        @elseif($event->type === \Eshop\Models\Cart\CartEvent::WARNING)
                            <em class="fas fa-exclamation-circle text-warning fs-5"></em>
                        @elseif($event->type === \Eshop\Models\Cart\CartEvent::ERROR)
                            <em class="fas fa-times-circle text-danger fs-5"></em>
                        @endif
                    </div>

                    <div x-data="{ open: false }" class="w-100 d-flex flex-column">
                        <a href="#" @click.prevent="open = !open" class="d-flex justify-content-between text-dark align-items-baseline text-decoration-none">
                            <div class="fw-500 me-2">{{ $event->title }}</div>
                            <em class="fas" :class="{ 'fa-chevron-down': !open, 'fa-chevron-up': open }"></em>
                        </a>

                        <div class="d-flex">
                            <div>{{ $event->created_at->isoFormat('lL HH:mm') }}</div>
                            <div class="mx-2">|</div>
                            <div>{{ $event->user?->fullname }}</div>
                        </div>

                        <ul x-show="open" x-cloak>
                            @if($event->details)
                                @foreach(collect($event->details)->flatten() as $detail)
                                    <li>{{ $detail }}</li>
                                @endforeach
                            @else
                                <li>Δεν υπάρχουν λεπτομέρειες</li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endforeach
            <div class="d-flex mb-3 align-items-start">
                <div class="pe-3">
                    <em class="fas fa-exclamation-circle text-primary fs-5"></em>
                </div>

                <div class="w-100 d-flex flex-column">
                    <a href="#" class="d-flex justify-content-between text-dark align-items-baseline text-decoration-none fw-500">
                        Δημιουργία καλαθιού
                    </a>

                    <div class="d-flex">
                        <div>{{ $cart->created_at->isoFormat('lL HH:mm') }}</div>
                        <div class="mx-2">|</div>
                        <div>{{ $cart->user?->fullname }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>