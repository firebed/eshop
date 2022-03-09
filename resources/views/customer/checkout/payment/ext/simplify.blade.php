<div id="simplify-checkout" class="vstack gap-3 col-12 col-xl-8 col-xxl-6">
    <div class="vstack gap-2 align-items-baseline">
        <div class="hstack"><em class="fas fa-lock text-alpha fs-4 me-2"></em><span>Ασφαλείς online αγορές</span></div>
    </div>

    <div>
        <label for="cc-number" class="form-label">{{ __("Credit Card Number") }}</label>
        <div class="input-group">
            <span class="input-group-text text-muted"><em class="fas fa-credit-card"></em></span>
            <input id="cc-number" type="text" class="form-control font-monospace" placeholder="{{ __("Credit Card Number") }}" maxlength="19" autocomplete="off" value=""/>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <label for="cc-expiry" class="form-label">Expiry Date:</label>
            <div class="input-group">
                <span class="input-group-text text-muted"><em class="fas fa-calendar-alt"></em></span>
                <input id="cc-expiry" class="form-control font-monospace" maxlength="5" placeholder="MM/YY">
            </div>
        </div>

        <div class="col-5">
            <label for="cc-cvc" class="form-label">CVC</label>
            <div class="input-group">
                <span class="input-group-text text-muted"><em class="fas fa-key"></em></span>
                <input id="cc-cvc" type="text" class="form-control font-monospace" maxlength="4" autocomplete="off" value="" placeholder="CVC"/>
            </div>
        </div>
    </div>

    <div>
        <img class="img-fluid" src="{{ asset('images/credit-cards.webp') }}" alt="MasterCard, Visa, Amex, Discover" width="150">
    </div>
</div>

@push('footer_scripts')
    <div id="3dsecure-modal" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe name="secure3d-frame" id="secure3d-frame" style="display: none"></iframe>
                    <form id="3dsecure-form" method="POST" target="secure3d-frame">
                        <input type="hidden" name="PaReq">
                        <input type="hidden" name="TermUrl">
                        <input type="hidden" name="MD">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://www.simplify.com/commerce/v1/simplify.js"></script>

    <script>
        // document.getElementById('cc-number').addEventListener('input', function (e) {
        //     if (e.inputType === 'deleteContentBackward') {
        //         return
        //     }
        //    
        //     const cursor = e.target.value.length;
        //    
        //     e.target.value = e.target.value.replace(/[^\d]/g, '').replace(/(.{4})/g, '$1 ');
        //     if (e.target.value.length >= 18) {
        //         e.target.value = e.target.value.trim();
        //     }
        // });
        //
        // document.getElementById('cc-expiry').addEventListener('input', function (e) {
        //     e.target.value = e.target.value.replace(/[^\d]/g, '').replace(/(.{2})/g, '$1/');
        // });

        const payment = document.querySelector('input[name=country_payment_method_id]:checked')
        const form = document.getElementById('checkout-form');

        function createSecure3dForm(secure3dData) {
            const secure3dForm = document.getElementById('3dsecure-form');
            secure3dForm.action = secure3dData.acsUrl;
            secure3dForm.querySelector('input[name=PaReq]').value = secure3dData.paReq;
            secure3dForm.querySelector('input[name=TermUrl]').value = secure3dData.termUrl;
            secure3dForm.querySelector('input[name=MD]').value = secure3dData.md;

            return secure3dForm;
        }

        function processPayment() {
            const payload = {
                cc_number: document.getElementById('cc-number').value.trim(),
                cc_expiry: document.getElementById('cc-expiry').value.trim(),
                cc_cvc: document.getElementById('cc-cvc').value.trim(),
            };

            axios.post(form.action, payload)
                .then(response => {
                    response = response.data;
                    console.log(response)
                    if (response.secure3D.isEnrolled) { // Step 1
                        const secure3dForm = createSecure3dForm(response.secure3D); // Step 2
                        const iframeNode = document.getElementById('secure3d-frame');

                        iframeNode.style.display = 'block';

                        const process3dSecureCallback = function (threeDsResponse) {
                            const simplifyDomain = 'https://www.simplify.com';
console.log(threeDsResponse.origin === simplifyDomain);
console.log(JSON.parse(threeDsResponse.data)['secure3d']['authenticated']);
                            window.removeEventListener('message', process3dSecureCallback);
                            // Step 4
                            if (threeDsResponse.origin === simplifyDomain && JSON.parse(threeDsResponse.data)['secure3d']['authenticated']) {
                                console.log("3DS authenticated")
                                const completePayload = {
                                    amount: response.total,
                                    currency: response.currency,
                                    description: response.description,
                                    token: response.token
                                };

                                axios.post(form.action, completePayload)
                                    .then(completeResponse => {
                                        if (completeResponse.success) {
                                            console.log("Charge successfull")
                                            // $('#simplify-payment-form').hide();
                                            // $('#simplify-success').show();
                                        }
                                        iframeNode.hide();
                                    });
                            } else {
                                console.log("Something went wrong.")
                                console.log(threeDsResponse);
                            }
                        };

                        iframeNode.addEventListener('load', function () {
                            window.addEventListener('message', process3dSecureCallback); // Step 3
                        });

                        secure3dForm.submit();
                    }
                });
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            if (payment.getAttribute('data-payment-method-name') !== 'credit_card_simplify') {
                return
            }

            processPayment();
            return false;
        });
    </script>
@endpush