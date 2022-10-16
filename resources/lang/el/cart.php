<?php


return [
    'see_your_cart' => 'Δες το καλάθι σου',

    'added_product' => 'Το προϊόν προστέθηκε στο καλάθι.',

    'status' => [
        'submitted' => 'Νέες παραγγελίες',
        'approved'  => 'Εγκρίθηκαν',
        'completed' => 'Ολοκληρώθηκαν',
        'shipped'   => 'Στάλθηκαν',
        'held'      => 'Σε αναμονή',
        'cancelled' => 'Ακυρώθηκαν',
        'rejected'  => 'Απορρίφθηκαν',
        'returned'  => 'Επιστράφηκαν',

        'action' => [
            'submitted' => 'Αναμονή προς έγκριση',
            'approved'  => 'Εγκρίθηκε',
            'completed' => 'Ολοκληρώθηκε',
            'shipped'   => 'Στάλθηκε',
            'held'      => 'Σε αναμονή',
            'cancelled' => 'Ακυρώθηκε',
            'rejected'  => 'Απορρίφθηκε',
            'returned'  => 'Επιστράφηκε'
        ]
    ],

    'channel' => [
        'instagram' => 'Instagram',
        'facebook'  => 'Facebook',
        'phone'     => 'Phone',
        'eshop'     => 'Eshop',
        'pos'       => 'POS',
        'other'     => 'Other',
        'skroutz'   => 'Skroutz'
    ],

    'events' => [
        'get_checkout_products' => 'Επίσκεψη σελίδας καλαθιού',

        'get_checkout_details' => 'Επίσκεψη σελίδας διεύθυνσης αποστολής & τιμολογίου',
        'set_checkout_details' => 'Ενημέρωση διεύθυνσης αποστολής & τιμολογίου',

        'get_checkout_payment' => 'Επίσκεψη σελίδας ολοκλήρωσης καλαθιού',
        'set_checkout_payment' => 'Ολοκλήρωση καλαθιού',

        'checkout_email' => 'Αποστολή email παραγγελίας στον πελάτη',

        'checkout_insufficient_quantity' => 'Αποτυχία παραγγελίας λόγω ανεπαρκής ποσότητας προϊόντων',
        'checkout_total_updated'         => 'Επανέναρξη διαδικασίας παραγγελίας λόγω αλλαγής τιμής προϊόντων',

        'paypal_checkout'        => 'Προσπάθεια πληρωμής μέσω PayPal',
        'stripe_checkout'        => 'Προσπάθεια πληρωμής μέσω Stripe',
        'stripe_action_required' => 'Προσπάθεια πληρωμής μέσω Stripe. Απαιτείται ενέργεια από τον πελάτη (3d secure).',
        'simplify_checkout'      => 'Προσπάθεια πληρωμής μέσω Εθνικής Τράπεζας',

        'order_viewed' => 'Προβολή παραγγελίας',

        'order-submitted'       => 'Υποβολή παραγγελίας',
        'order-submitted-email' => 'Ενημέρωση πελάτη μέσω mail',

        'order-approved'       => 'Έγκριση παραγγελίας',
        'order-approved-email' => 'Ενημέρωση πελάτη μέσω mail',

        'order-completed'       => 'Ολοκλήρωση παραγγελίας',
        'order-completed-email' => 'Ενημέρωση πελάτη μέσω mail',

        'order-held'       => 'Αναμονή παραγγελίας',
        'order-held-email' => 'Ενημέρωση πελάτη μέσω mail',

        'order-shipped'       => 'Η παραγγελία στάλθηκε',
        'order-shipped-email' => 'Ενημέρωση πελάτη μέσω mail',

        'order-cancelled'       => 'Η παραγγελία ακυρώθηκε',
        'order-cancelled-email' => 'Ενημέρωση πελάτη μέσω mail',

        'order-rejected'       => 'Η παραγγελία απορρίφθηκε',
        'order-rejected-email' => 'Ενημέρωση πελάτη μέσω mail',

        'order-returned'       => 'Η παραγγελία επιστράφηκε',
        'order-returned-email' => 'Ενημέρωση πελάτη μέσω mail',

        'order_paid' => 'Η παραγγελία πληρώθηκε.',

        'voucher-updated' => 'Ενημέρωση voucher',

        'abandonment-email-1' => 'Αποστολή 1ης υπενθύμισης καλαθιού',
        'abandonment-email-2' => 'Αποστολή 2ης υπενθύμισης καλαθιού',
        'abandonment-email-3' => 'Αποστολή 3ης υπενθύμισης καλαθιού',

        'abandonment-email-1-viewed' => 'Άνοιγμα μηνύματος 1ης υπενθύμισης',
        'abandonment-email-2-viewed' => 'Άνοιγμα μηνύματος 2ης υπενθύμισης',
        'abandonment-email-3-viewed' => 'Άνοιγμα μηνύματος 3ης υπενθύμισης',

        'resume-abandoned-1' => 'Συνέχιση καλαθιού (1η υπενθύμιση)',
        'resume-abandoned-2' => 'Συνέχιση καλαθιού (2η υπενθύμιση)',
        'resume-abandoned-3' => 'Συνέχιση καλαθιού (3η υπενθύμιση)',

        'first_abandonment_subject'  => '🛒 Το καλάθι σας περιμένει',
        'second_abandonment_subject' => '🛒 Το καλάθι σας περιμένει',
        'third_abandonment_subject'  => '🚨 Το καλάθι σας περιμένει',

        'abandoned-email-title' => 'Παρατηρήσαμε πως αφήσατε προϊόντα στο καλάθι αγορών σας.',
        'abandoned-email-help'  => 'Αν έχετε απορίες ή χρειάζεστε βοήθεια μπορείτε να επικοινωνήσετε μαζί μας.',
    ]
];