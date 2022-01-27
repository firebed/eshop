<div class="modal fade" id="clients-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Επιλογή πελάτη</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div class="mb-3 bg-light p-3">
                    <input type="search" id="client-search-input" class="form-control" placeholder="Αναζήτηση">
                </div>

                @include('eshop::dashboard.invoice.partials.clients')
            </div>
        </div>
    </div>
</div>

@push('footer_scripts')
    <script>
        const clientsTable = document.querySelector("#clients-modal .table-responsive")
        
        function debounce(func, ms) {
            let timeout;
            return function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, arguments), ms);
            };
        }
        
        function render() {
            clientsTable.querySelectorAll('button').forEach(btn => {
                btn.addEventListener('click', (c) => {
                    c.preventDefault()

                    document.getElementById("client-id").value = btn.getAttribute('data-id')
                    document.getElementById("client-title").value = btn.getAttribute('data-title')

                    bootstrap.Modal.getInstance(document.getElementById('clients-modal')).hide()
                    
                    Livewire.emit('setVatPercent', btn.getAttribute('data-country'))
                })
            })
        }

        let clientSearchInput = document.getElementById('client-search-input');
        clientSearchInput.addEventListener('input', debounce((e) => {
            const term = e.target.value.trim();

            axios.post('{{ route('invoices.search_clients') }}', {term})
                .then(res => {
                    clientsTable.outerHTML = res.data
                    render()                    
                })
        }, 100))
        
        render()
    </script>
@endpush