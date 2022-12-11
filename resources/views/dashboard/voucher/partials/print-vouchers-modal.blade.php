<form action="{{ route('carts.print-vouchers') }}" method="post" target="_blank">
    @csrf
    <div x-ref="modal" class="modal fade" id="print-vouchers-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Εκτύπωση</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach($carts as $cart)
                        <input type="hidden" name="ids[]" value="{{ $cart->id }}">
                    @endforeach
                    
                    <x-bs::input.checkbox name="with_carts" id="with-carts">Εκτύπωση των δελτίων παραγγελίας</x-bs::input.checkbox>
                    <x-bs::input.checkbox name="two_sided" id="2-faced">Εκτύπωση διπλής όψης</x-bs::input.checkbox>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Κλείσιμο</button>
                    <button type="submit" class="btn btn-primary" x-bind:disabled="loading">
                        <em x-show="!loading" class="fa fa-print me-2"></em>
                        <em x-show="loading" x-cloak class="fa fa-spinner fa-spin me-2"></em>
                        Εκτύπωση
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>