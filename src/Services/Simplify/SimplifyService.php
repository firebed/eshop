<?php

namespace Eshop\Services\Simplify;

require_once(__DIR__ . '/../../../lib/simplify/Simplify.php');

use Error;
use Illuminate\Http\Request;
use Simplify;
use Simplify_CardToken;
use Simplify_Event;
use Simplify_Payment;

/**
 * Documentation: https://ibanknbg.simplify.com/commerce/documentation/index
 * Webhooks:      https://ibanknbg.simplify.com/commerce/docs/misc/webhooks
 */
class SimplifyService
{
    public function __construct()
    {
        Simplify::$publicKey = config('simplify.public_key');
        Simplify::$privateKey = config('simplify.private_key');
    }

    public function createCardToken(string $number, string $expMonth, string $expYear, string $cvc, string $addressCity)
    {
        return Simplify_CardToken::createCardToken([
            'card'                => [
                'expMonth'    => $expMonth,
                'expYear'     => $expYear,
                'addressCity' => $addressCity,
                'cvc'         => $cvc,
                'number'      => $number
            ]
        ]);
    }

    public function createCardTokenUsing3dSecure(float $total, string $number, string $expMonth, string $expYear, string $cvc, string $addressCity)
    {
        $amount = $this->getTotal($total);

        return Simplify_CardToken::createCardToken([
            'card'                => [
                'expMonth'    => $expMonth,
                'expYear'     => $expYear,
                'addressCity' => $addressCity,
                'cvc'         => $cvc,
                'number'      => $number
            ],
            'secure3DRequestData' => [
                'amount'      => $amount,
                'currency'    => 'EUR',
                'description' => config('app.name')
            ]
        ]);
    }
    
    public function createPayment(float $total, string $token)
    {
        $amount = $this->getTotal($total);

        return Simplify_Payment::createPayment([
            'amount'      => $amount,
            'token'       => $token,
            'description' => 'Purchase from ' . config('app.name'),
            'reference'   => '',
            'currency'    => 'EUR'
        ]);
    }

    public function findCardToken($token)
    {
        return Simplify_CardToken::findCardToken($token);
    }

    /**
     * {
     *      "event": {
     *          "name": "payment.create",
     *          "data": {
     *              "card": {
     *                  "id": "8i5RMT",
     *                  "type": "MASTERCARD",
     *                  "last4": "4444",
     *                  "expMonth": 12,
     *                  "expYear": 14,
     *                  "dateCreated": 1380548895740
     *              },
     *              "disputed": false,
     *              "amount": 1100,
     *              "amountRemaining": 1100,
     *              "currency": "USD",
     *              "refunded": false,
     *              "authCode": "1380548896616",
     *              "paymentStatus": "APPROVED",
     *              "dateCreated": 1380548896633,
     *              "paymentDate": 1380548896617,
     *              "id": "5c8jzi",
     *              "fee": 61
     *          }
     *      }
     * }
     */
    public function webhook(Request $request)
    {
        logger(Simplify_Event::createEvent(array(
            'payload' => $request->input()
        )));
    }

    private function getTotal(float $total): int
    {
        $amount = (int)round($total, 2) * 100;

        if ($amount < 50 || $amount > 9999900) {
            throw new Error("Amount must be between 50 and 9999900");
        }

        return $amount;
    }
}