@extends('layouts.master', ['title' => 'Τρόποι πληρωμής'])

@php
    $url = env('APP_URL');
    $base = basename($url);
    $link = "<a href='$url'>$base</a>";

    $paymentFee = 1.5;
    $bankAccounts = Lang::get('company.bank_accounts');
@endphp

@section('main')
    <div class="container-fluid py-4 bg-white">
        <div class="container">
            <h1 class="fs-3 mb-3">Τρόποι πληρωμής</h1>

            <p>Το ηλεκτρονικό κατάστημα {!! $link !!} υποστηρίζει τους εξής τρόπους πληρωμής:</p>

            <ol class="vstack gap-4">
                <li class="fw-bold">
                    <div>Αντικαταβολή</div>
                    <div class="fw-normal">Η πληρωμή της παραγγελίας σας γίνεται κατά την παραλαβή της με καταβολή του ποσού στον διανομέα της εταιρίας courier που συνεργαζόμαστε. Το κόστος της αντικαταβολής είναι {{ format_currency($paymentFee) }}.</div>
                </li>

                <li class="fw-bold">
                    <div>PayPal</div>
                    <div class="fw-normal">Ο PayPal είναι ο γρηγορότερος τρόπος διεκπαιρέωσης της παραγγελίας σας. Αν επιλέγετε το PayPal σαν τρόπο πληρωμής, δεν θα υπάρχει καμία επιπλέον επιβάρυνση στην αξία της παραγγελίας.</div>
                </li>

                <li class="fw-bold">
                    <div>Πιστωτική κάρτα</div>
                    <div class="fw-normal">Μπορείτε να κάνετε online πληρωμές με την την πιστωτική κάρτα. Δεχόμαστε όλες της πιστωτικές κάρτες <strong>MasterCard</strong> και <strong>Visa</strong>.</div>
                </li>

                <li class="fw-bold">
                    <div>Τραπεζικό Έμβασμα</div>
                    <div class="fw-normal mb-3">Μπορείτε να καταθέσετε το ποσό της παραγγελίας στους παρακάτω λογαριασμούς. Στο καταθετήριο τραπέζης πρέπει οπωσδήποτε να αναγράφεται το ονοματεπώνυμο σας και ο αριθμός της παραγγελίας που σας έχει σταλεί στο email σας κατά την ολοκλήρωσή της.</div>

                    <ol>
                        @foreach ($bankAccounts as $bank => $account)
                            <li class="mb-3">{{ $bank }}
                                <div class="fw-normal">
                                    <div>ΑΡΙΘΜΟΣ ΛΟΓΑΡΙΑΣΜΟΥ: {{ $account['number'] }}</div>
                                    <div>IBAN: {{ $account['iban'] }}</div>
                                    <div>Όνομα δικαιούχου: {{ $account['owner'] }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ol>

                    <div class="vstack gap-1 fw-500">
                        <div><em class="fas fa-chevron-right"></em> Τα προϊόντα σας θα αποσταλούν, μόλις καταχωρηθεί σε εμάς το ποσό της παραγγελίας σας και επιβεβαιωθεί από το αρμόδιο τμήμα της εταιρείας μας.</div>
                        <div><em class="fas fa-chevron-right"></em> Τα διατραπεζικά έξοδα που τυχόν προκύπτουν σε μια κατάθεση επιβαρύνουν εξ’ολοκλήρου τον πελάτη.</div>
                    </div>
                </li>
            </ol>
        </div>
    </div>
@endsection