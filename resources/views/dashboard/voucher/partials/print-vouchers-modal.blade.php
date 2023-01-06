<form action="{{ route('carts.print-vouchers') }}" method="post" target="_blank">
    @csrf
    <div x-ref="modal" class="modal fade" id="print-vouchers-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Εκτύπωση</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div x-data="{ joinType: null }" x-effect="console.log(carts)" class="modal-body">
                    @foreach($cartIds as $cartId)
                        <input type="hidden" name="ids[]" value="{{ $cartId }}">
                    @endforeach

                    <x-bs::input.radio x-model="joinType" name="joinType" value="carts" id="with-carts">
                        <div class="fw-500">Εκτύπωση των δελτίων παραγγελίας</div>
                        <p class="small text-secondary">Συμπερίληψη του εκάστοτε δελτίου παραγγελίας πριν τον κωδικό αποστολής.</p>
                    </x-bs::input.radio>

                    <div class="ms-4">
                        <x-bs::input.checkbox x-bind:disabled="joinType !== 'carts'" name="two_sided" id="2-faced">
                            <div class="fw-500">Εκτύπωση διπλής όψης</div>
                            <p class="small text-secondary">Αναδιάταξη σελίδων έτσι ώστε οι κωδικοί αποστολής να εκτυπώνονται στο πίσω μέρος του δελτίου παραγγελίας.</p>
                        </x-bs::input.checkbox>
                    </div>

                    <x-bs::input.radio x-model="joinType" name="joinType" value="vouchers" id="join-vouchers">
                        <div class="fw-500">Συγχώνευση κωδικών αποστολής 1x3</div>
                        <p class="small text-secondary">Κάθε σελίδα θα περιλαμβάνει έως και 3 κωδικούς αποστολής οριζόντια για εξοικονόμηση χαρτιού.</p>
                    </x-bs::input.radio>
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