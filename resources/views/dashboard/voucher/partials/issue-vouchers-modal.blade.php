<div x-data="{ loading:false, errors: [] }"
     x-on:create-vouchers-started.window="loading = true"
     x-on:create-vouchers-finished.window="loading = false"
     class="modal fade" 
     data-bs-backdrop="static" id="issue-vouchers-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Έκδοση κωδικών αποστολής</h5>
                <button type="button" :disabled="loading" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-2">
                    <template x-for="error in errors">
                        <div class="text-danger fw-500">
                            <em class="fa fa-exclamation-circle me-2"></em>
                            <span x-text="error"></span>
                        </div>
                    </template>
                </div>
                
                <p><span class="fw-500">Προσοχή!</span> Πρόκειται να εκδώσετε <span x-text="ids.length"></span> κωδικούς αποστολής.
                    Μετά την έκδοση θα έχετε ακόμα τη δυνατότητα ακύρωσης των αποστολών σε περίπτωση που χρειαστεί.</p>
                
                <p><em class="fa fa-exclamation-circle text-warning"></em> Θα γίνει χρέωση των κωδικών βάση του συμβολαίου σας.</p>

                <p :class="loading ? 'visible' : 'invisible'" x-cloak class="text-secondary small mb-1">Παρακαλώ περιμένετε...</p>
                
                <div class="progress mb-3">
                    <div class="progress-bar bg-success" role="progressbar" :style="`width: ${success()}%`">
                        <span x-text="`${success()}%`"></span>
                    </div>
                </div>
                
                <div class="d-flex gap-5">
                    <div class="fw-500"><span>Εκδόθηκαν: </span><span class="text-success" x-text="ids.length"></span></div>
                    <div class="fw-500"><span>Απέτυχαν: </span><span class="text-danger" x-text="ids.length - vouchers.length"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button x-bind:disabled="loading" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Άκυρο</button>
                <button id="issue-vouchers" x-bind:disabled="loading" type="button" class="btn btn-primary">
                    <em x-show="loading" x-cloak class="fa fa-spinner fa-spin me-2"></em>
                    Έκδοση
                </button>
            </div>
        </div>
    </div>
</div>