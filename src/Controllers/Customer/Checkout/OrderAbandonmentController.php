<?php

namespace Eshop\Controllers\Customer\Checkout;

use Eshop\Actions\Order\ResumeCart;
use Eshop\Actions\ReportError;
use Eshop\Controllers\Customer\Controller;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderAbandonmentController extends Controller
{
    public function show(Request $request, string $locale, int $cartId, int $eventId, ResumeCart $resumeCart): RedirectResponse
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

    public function track(Request $request, string $locale, int $cartId, int $eventId): Response
    {
        $image = base64_decode("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII");
        $response = response($image)->header('Content-Type', 'image/png');

        if (!$request->hasValidSignature()) {
            return $response;
        }
        
        $cart = Cart::find($cartId);
        if ($cart === null) {
            return $response;
        }
        
        $event = $cart->events()->find($eventId);
        if ($event === null) {
            return $response;
        }

        if ($event->action === CartEvent::ABANDONMENT_EMAIL_1) {
            CartEvent::info($cart->id, CartEvent::ABANDONMENT_EMAIL_1_VIEWED);
        } elseif ($event->action === CartEvent::ABANDONMENT_EMAIL_2) {
            CartEvent::info($cart->id, CartEvent::ABANDONMENT_EMAIL_2_VIEWED);
        } else if ($event->action === CartEvent::ABANDONMENT_EMAIL_3) {
            CartEvent::info($cart->id, CartEvent::ABANDONMENT_EMAIL_3_VIEWED);
        }
        
        return $response;
    }
}
