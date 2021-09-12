<div class="container-fluid py-4">
    <div class="container-xxl">
        <div class="row row-cols-2 row-cols-sm-2 row-cols-xl-4 g-4">
            <div class="col vstack">
                <div class="fw-500 mb-2">Πληροφορίες</div>
                <a href="{{ route('pages.show', [app()->getLocale(), 'terms-of-service']) }}" class="text-dark text-hover-underline">Όροι χρήσης</a>
                <a href="{{ route('pages.show', [app()->getLocale(), 'data-protection']) }}" class="text-dark text-hover-underline">Προσωπικά δεδομένα</a>
                <a href="{{ route('pages.show', [app()->getLocale(), 'return-policy']) }}" class="text-dark text-hover-underline">Πολιτική επιστροφών</a>
                <a href="{{ route('pages.show', [app()->getLocale(), 'cancellation-policy']) }}" class="text-dark text-hover-underline">Πολιτική ακύρωσης</a>
                <a href="{{ route('pages.show', [app()->getLocale(), 'secure-transactions']) }}" class="text-dark text-hover-underline">Ασφάλεια συναλλαγών</a>
            </div>

            <div class="col vstack">
                <div class="fw-500 mb-2">Υποστήριξη</div>
                <a href="{{ route('pages.show', [app()->getLocale(), 'shipping-methods']) }}" class="text-dark text-hover-underline">Τρόποι αποστολής</a>
                <a href="{{ route('pages.show', [app()->getLocale(), 'payment-methods']) }}" class="text-dark text-hover-underline">Τόποι πληρωμής</a>
            </div>

            <div class="col vstack">
                <div class="fw-500 mb-2">Λογαριασμός</div>
                <a href="{{ route('login', app()->getLocale()) }}" class="text-dark text-hover-underline">Σύνδεση</a>
                <a href="{{ route('register', app()->getLocale()) }}" class="text-dark text-hover-underline">Εγγραφή</a>
                <a href="{{ route('register', app()->getLocale()) }}" class="text-dark text-hover-underline">Οι παραγγελίες μου</a>
                <a href="{{ route('register', app()->getLocale()) }}" class="text-dark text-hover-underline">Οι διευθύνσεις μου</a>
            </div>

            <div class="col vstack">
                <div class="fw-500 mb-2">Λογαριασμός</div>
                <a href="{{ route('login', app()->getLocale()) }}" class="text-dark text-hover-underline">Σύνδεση</a>
                <a href="{{ route('register', app()->getLocale()) }}" class="text-dark text-hover-underline">Εγγραφή</a>
                <a href="{{ route('register', app()->getLocale()) }}" class="text-dark text-hover-underline">Οι παραγγελίες μου</a>
                <a href="{{ route('register', app()->getLocale()) }}" class="text-dark text-hover-underline">Οι διευθύνσεις μου</a>
            </div>
        </div>

        <hr>
        
    </div>
</div>