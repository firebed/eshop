@extends('eshop::customer.pages.master', [
    'title' => __("Payment methods"),
    'description' => __("Payment methods")
])

@php
    $url = env('APP_URL');
    $email = __('company.email');
    $base = basename($url);
    $link = "<a href='$url'>$base</a>";

    $paymentFee = 0;
    $bankAccounts = __('company.bank_accounts');
    if (!is_array($bankAccounts)) {
        $bankAccounts = [];
    }
@endphp

@section('main')
    <div class="container-fluid py-4 bg-white">
        <div class="container">
            <h1 class="fs-3 mb-3">Τρόποι πληρωμής</h1>

            <ol class="vstack gap-4">
                <li>
                    <h2 class="fw-500 fs-5">Αντικαταβολή</h2>
                    <p class="fw-normal">Μπορείτε να πληρώσετε με μετρητά το courier κατά τη παραλαβή του δέματος σας.</p>

                    <div class="d-grid border shadow-sm rounded bg-light">
                        <span class="border-start border-5 rounded-start border-primary p-3">
                            <em class="fas fa-check-circle me-3 text-green-400"></em>
                            Το κόστος της αντικαταβολής είναι {{ format_currency($paymentFee) }} για όλες τις αποστολές εντός Ελλάδος.
                            <br>
                            <em class="fas fa-exclamation-circle me-3 text-red-400"></em>
                            Για παραγγελίες σε Ευρώπη και Κύπρο η αντικαταβολή δεν υποστηρίζεται.
                        </span>
                    </div>
                </li>

                <li>
                    <h2 class="fw-500 fs-5">Πιστωτική κάρτα</h2>
                    <div class="fw-normal">Πληρώστε με οποιαδήποτε κάρτα Visa, Mastercard, Maestro, μέσω των ασφαλών υπηρεσιών της Εθνικής τράπεζας / Braintree / Stripe.</div>
                </li>

                <li>
                    <h2 class="fw-500 fs-5">PayPal</h2>
                    <p class="fw-normal">Εάν έχετε λογαριασμό Paypal και επιθυμείτε να πληρώσετε το τελικό ποσό μέσω αυτής της διαδικασίας μπορείτε να επιλέξετε «πληρωμή Paypal» και η διαδικασία θα ολοκληρωθεί χωρίς να μεταφερθείτε στην ιστοσελίδα του Paypal.</p>
                    <p class="mb-0">Από μέρους μας δεν υπάρχει επιπλέον χρέωση εάν επιλέξετε αυτό τον τρόπο πληρωμής.</p>
                </li>

                <li>
                    <h2 class="fw-500 fs-5">Τραπεζική κατάθεση</h2>
                    <p class="fw-normal">Η κατάθεση του ποσού προς πληρωμή μπορεί να γίνει εντός 3 ημερών στον παρακάτω τραπεζικό λογαριασμό. Έπειτα, στείλτε μας το αποδεικτικό κατάθεσης στο {{ $email }} ώστε να προχωρήσουμε στην εκτέλεση της παραγγελία σας. Πέραν των 3 ημερών και χωρίς
                        προειδοποίηση ακυρώνεται η παραγγελία.</p>

                    <ol>
                        @foreach ($bankAccounts as $account)
                            <li class="mb-3">{{ $account['bank'] }}
                                <div class="fw-normal">
                                    <div>ΑΡΙΘΜΟΣ ΛΟΓΑΡΙΑΣΜΟΥ: {{ $account['number'] }}</div>
                                    <div>IBAN: {{ $account['iban'] }}</div>
                                    <div>Όνομα δικαιούχου: {{ $account['owner'] }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </li>
            </ol>
        </div>
    </div>
@endsection
