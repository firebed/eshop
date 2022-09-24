<form method="POST" action="{{ route('blogs.publish', $blog) }}">
    @csrf
    <div class="modal fade" id="publish-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Δημοσίευση</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Αποστολή σε {{ $mailCount }} παραλήπτες;
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Άκυρο</button>
                    <button type="submit" class="btn btn-primary">Αποστολή</button>
                </div>
            </div>
        </div>
    </div>
</form>