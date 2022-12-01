<div x-init="$el.addEventListener('show.bs.modal', () => setup())" class="modal fade" data-bs-backdrop="static" id="issue-vouchers-modal" tabindex="-1">
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

                <div class="border-start border-3 border-primary alert-primary p-3 mb-3"><em class="fa fa-info-circle fa-sm"></em> Πρόκειται να εκδώσετε <span></span> κωδικούς αποστολής.
                    Μετά την έκδοση θα έχετε ακόμα τη δυνατότητα ακύρωσης των αποστολών σε περίπτωση που χρειαστεί.
                </div>

                <p><em class="fa fa-exclamation-circle text-warning"></em> Θα γίνει χρέωση των κωδικών βάση του συμβολαίου σας.</p>

                <template x-if="rows.length > 0">
                    <div>
                        <div class="text-secondary small mb-1">
                            <span>Κωδικοί προς έκδοση: <span x-text="rows.length"></span>.</span>
                            <span :class="loading ? 'visible' : 'invisible'" x-cloak>Παρακαλώ περιμένετε...</span>
                        </div>
                        
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success" role="progressbar" :style="`width: ${successRate()}`">
                                <span x-text="successRate()"></span>
                            </div>
                        </div>

                        <div class="d-flex gap-5">
                            <div class="fw-500"><span>Εκδόθηκαν: </span><span x-text="successCount" class="text-success"></span></div>
                            <div class="fw-500"><span>Απέτυχαν: </span><span x-text="failedCount" class="text-danger"></span></div>
                        </div>
                    </div>
                </template>

                <template x-if="rows.length === 0">
                    <div class="fw-500 mb-3"><em class="fa fa-check-circle text-success"></em> Όλοι οι κωδικοί αποστολής έχουν εκδοθεί.</div>
                </template>
            </div>

            <div class="modal-footer">
                <button x-bind:disabled="loading" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Κλείσιμο</button>
                <button x-show="rows.length > 0" @click="dispatch()" x-bind:disabled="loading" type="button" class="btn btn-primary">
                    <em x-show="loading" x-cloak class="fa fa-spinner fa-spin me-2"></em>
                    Έκδοση
                </button>
            </div>
        </div>
    </div>
</div>