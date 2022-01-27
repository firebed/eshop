@extends('eshop::dashboard.layouts.product', ['product' => $product->isVariant() ? $product->parent : $product])

@section('content')
    <div class="vstack">
        @forelse($audits as $audit)
            <div class="row gx-5 align-items-baseline">
                <div class="col-auto small">
                    <div class="fw-500">{{ $audit->created_at->isoFormat('D MMM YY') }}</div>
                    <div class="text-muted">{{ $audit->created_at->isoFormat('H:mm') }}</div>
                </div>
                
                <div class="col-9 {{ !$loop->last ? 'border-start' : '' }} pb-5 position-relative d-flex">
                    <span class="position-absolute start-0 translate-middle-x rounded-circle bg-white" style="width: 20px; height: 20px; top: 3px; border: 5px solid hotpink">
                    </span>
                    
                    <a href="{{ route('audits.show', $audit) }}" data-bs-audit class="text-decoration-none text-gray-700 d-grid">
                        <span class="fw-500">Επεξεργασία</span>
                        <span class="small text-muted">{{ $audit->user->fullname }}</span>                        
                    </a>
                </div>
            </div>
        @empty
        @endforelse
    </div>

    <div class="modal fade" id="audit-modal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="h5 modal-title" id="exampleModalLabel">Έλεγχος</div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body scrollbar bg-light">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script>
        const auditModal = document.getElementById('audit-modal')
        const modal = new bootstrap.Modal(auditModal)
        
        document.querySelectorAll('a[data-bs-audit]').forEach(a => {
            a.addEventListener('click', clk => {
                clk.preventDefault()
                
                axios.get(a.href).then(res => {
                    auditModal.querySelector('.modal-body').innerHTML = res.data
                    modal.show()
                })
            })
        })
    </script>
@endpush