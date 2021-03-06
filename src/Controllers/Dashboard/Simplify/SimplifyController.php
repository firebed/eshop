<?php

namespace Eshop\Controllers\Dashboard\Simplify;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Services\Simplify\SimplifyService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class SimplifyController extends Controller
{
    public function index(): Renderable
    {
        return $this->view('simplify.index');
    }

    public function checkout(Request $request)
    {
        $total = 50; // 50 cents

        $simplify = new SimplifyService();

        if ($request->missing('token')) {
            $request->validate([
                'cc_number' => ['required', 'int', 'digits:16'],
                'cc_expiry' => ['required', 'string', 'regex:/^\d{2}\/\d{2}$/'],
                'cc_cvc'    => ['required', 'string']
            ]);

            [$month, $year] = explode("/", $request->input('cc_expiry'));

            $response = $simplify->createCardTokenUsing3dSecure(
                $total,
                $request->input('cc_number'),
                $month,
                $year,
                $request->input('cc_cvc'),
                'Xanthi'
            );

            return response()->json([
                'total'       => $total,
                'currency'    => 'EUR',
                'description' => 'Purchase',
                'token'       => $response->id,
                'secure3D'    => $response->card->secure3DData,
            ]);
        }

        $response = $simplify->createPayment($total, $request->input('token'));

        if ($response->paymentStatus === "APPROVED") {
            return response()->json($response);
        }
        
        dd($response);
    }
}