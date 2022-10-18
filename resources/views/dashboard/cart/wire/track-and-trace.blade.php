<div>
    <x-bs::button.primary wire:click.prevent="trace()" data-bs-toggle="offcanvas" data-bs-target="#track-and-trace">Αναζήτηση Αποστολής</x-bs::button.primary>

    <div wire:ignore.self class="offcanvas offcanvas-end shadow px-0" tabindex="-1" id="track-and-trace" style="width: 500px">
        <div class="offcanvas-header border-bottom">
            <div class="fs-5 fw-500">Track & Trace</div>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body scrollbar">
            <div wire:loading class="text-center w-100">
                <div class="mb-3"><em class="fas fa-spinner fa-spin fa-3x text-gray-400"></em></div>
                <div class="small text-secondary">Παρακαλώ περιμένετε όσο επικοινωνούμε<br>με τη μεταφορική εταιρεία.</div>
            </div>

            <div wire:loading.remove>
                @foreach($checkpoints as $checkpoint)
                    <div wire:key="checkpoint-{{ $loop->index }}" class="d-flex mb-3 align-items-baseline">
                        <em class="fas fa-arrow-right me-2 text-green-500"></em>
                        
                        <div>
                            <div class="fw-500">{{ $checkpoint['title'] }}</div>
                            <div class="small text-secondary">{{ $checkpoint['description'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>