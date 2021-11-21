@extends('eshop::customer.pages.master', [
    'title' => __("Shipping methods"),
    'description' => __("Shipping methods")
])

@section('main')
    <div class="container-fluid py-4 bg-white">
        <div class="container">
            <h1 class="fs-3 mb-3">Τρόποι αποστολής</h1>

            <ol class="vstack gap-4">
                <li>
                    <h2 class="fw-500 fs-5">Εντός Ελλάδας</h2>
                    <p class="fw-normal">Οι αποστολές ενός Ελλάδας πραγματοποιούνται με τις εταιρείες <span class="fw-500">Γενική Ταχυδρομική</span> και <span class="fw-500">ACS Courier</span>.</p>
                    <p class="fw-normal">Το κόστος αποστολής με την Γενική Ταχυδρομική είναι {{ format_currency(2.5) }} και με την ACS Courier είναι {{ format_currency(2.5) }} έως 4 κιλά.</p>

                    <div class="d-grid border shadow-sm rounded bg-light">
                        <div class="vstack gap-2 border-start border-5 rounded-start border-primary p-3">
                            <div>
                                <em class="fas fa-check-circle me-3 text-green-400"></em>
                                Σε κάθε περίπτωση για παραγγελίες άνω των {{ format_currency(50) }} η αποστολή γίνεται δωρεάν, ενώ το κόστος αντικαταβολής είναι {{ format_currency(2) }}.
                            </div>

                            <div>
                                <em class="fas fa-exclamation-circle me-3 text-red-400"></em>
                                Κάθε επιπλέον κιλό (πάνω από 4 κιλά) χρεώνεται επιπλέον με {{ format_currency(1) }} το κιλό.
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <h2 class="fw-500 fs-5">Κύπρο</h2>
                    <p class="fw-normal">Οι αποστολές για Κύπρο πραγματοποιούνται με την <span class="fw-500">ACS Courier</span>. Το κόστος αποστολής είναι {{ format_currency(14) }}.</p>
                </li>
            </ol>
        </div>
    </div>
@endsection
