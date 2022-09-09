<?php

namespace Eshop\Controllers\Customer\Checkout;

use Eshop\Actions\Order\ResumeCart;
use Eshop\Actions\ReportError;
use Eshop\Controllers\Customer\Controller;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderAbandonmentController extends Controller
{
    public function __invoke(Request $request, string $locale, int $cartId, int $eventId, ResumeCart $resumeCart): RedirectResponse
    {
        $cart = Cart::find($cartId);

        $checkoutUrl = redirect()->route('checkout.products.index', $locale);
        $fallbackUrl = redirect()->route('home', $locale);

        if (!$request->hasValidSignature()) {
            return $fallbackUrl;
        }

        if ($cart === null || $cart->isSubmitted()) {
            return $checkoutUrl;
        }

        $event = $cart->events()->findOrFail($eventId);
        DB::beginTransaction();
        try {
            $resumeCart->handle($cart);

            if ($event->action === CartEvent::ABANDONMENT_EMAIL_1) {
                CartEvent::resumeAbandoned($cart->id, CartEvent::RESUME_ABANDONED_1);
            } elseif ($event->action === CartEvent::ABANDONMENT_EMAIL_2) {
                CartEvent::resumeAbandoned($cart->id, CartEvent::RESUME_ABANDONED_2);
            } else if ($event->action === CartEvent::ABANDONMENT_EMAIL_3) {
                CartEvent::resumeAbandoned($cart->id, CartEvent::RESUME_ABANDONED_3);
            }

            DB::commit();
            return $checkoutUrl;
        } catch (Throwable $e) {
            DB::rollBack();

            $reporter = new ReportError();
            $reporter->handle($e->getMessage(), $e->getTraceAsString());
        }

        return $fallbackUrl;
    }
}
