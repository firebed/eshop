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
        $simplify = new SimplifyService();
        
        if ($request->missing('token')) {
            $request->validate([
                'cc_number' => ['required', 'int', 'digits:16'],
                'cc_expiry' => ['required', 'string', 'regex:/^\d{2}\/\d{2}$/'],
                'cc_cvc'    => ['required', 'string']
            ]);
            
            [$month, $year] = explode("/", $request->input('cc_expiry'));

            $result = $simplify->createCardToken(
//                50,
                $request->input('cc_number'),
                $month,
                $year,
                $request->input('cc_cvc'),
                'Xanthi'
            );

            return response()->json([
                'card' => $result->card,
                '3dsecure' => $result->card->secure3DData,
                'id'   => $result->id,
            ]);
        }

        $response = $simplify->createPayment(50, $request->input('token'));

        return response()->json($response);
    }
}